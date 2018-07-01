import tornado
import tornado.ioloop
import tornado.web
import os, uuid
import re
import face_recognition.api as face_recognition
import PIL.Image
import numpy as np
from tornado.log import enable_pretty_logging
enable_pretty_logging()

__UPLOADS__ = "uploads/"
__KNOW_FOLDER__ = "faces/known/"

class CompareFaces():

        def scan_known_people(self):
                known_names = []
                known_face_encodings = []

                for file in self.image_files_in_folder(__KNOW_FOLDER__):
                        basename = os.path.splitext(os.path.basename(file))[0]
                        img = face_recognition.load_image_file(file)
                        encodings = face_recognition.face_encodings(img)

                        if len(encodings) > 1:
                                print("WARNING: More than one face found in {}. Only considering the first face.".format(file))

                        if len(encodings) == 0:
                                print("WARNING: No faces found in {}. Ignoring file.".format(file))
                        else:
                                known_names.append(basename)
                                known_face_encodings.append(encodings[0])

                return known_names, known_face_encodings


        def print_result(self, name, distance, show_distance=False):
                if show_distance:
                        return "{},{}".format(name, distance)
                else:
                        return "{}".format(name)


        def test_image(self, image_to_check, tolerance=0.6, show_distance=False):
                unknown_image = face_recognition.load_image_file(image_to_check)

                # Scale down image if it's giant so things run a little faster
                if max(unknown_image.shape) > 1600:
                        pil_img = PIL.Image.fromarray(unknown_image)
                        pil_img.thumbnail((1600, 1600), PIL.Image.LANCZOS)
                        unknown_image = np.array(pil_img)

                unknown_encodings = face_recognition.face_encodings(unknown_image)

                known_names, known_face_encodings = self.scan_known_people()

                for unknown_encoding in unknown_encodings:
                        distances = face_recognition.face_distance(known_face_encodings, unknown_encoding)
                        result = list(distances <= tolerance)

                        if True in result:
                                return [self.print_result(name, distance, show_distance) for is_match, name, distance in zip(result, known_names, distances) if is_match]
                        else:
                                return self.print_result("unknown_person", None, show_distance)

                if not unknown_encodings:
                        # print out fact that no faces were found in image
                        return self.print_result("no_persons_found", None, show_distance)


        def image_files_in_folder(self, folder):
                return [os.path.join(folder, f) for f in os.listdir(folder) if re.match(r'.*\.(jpg|jpeg|png)', f, flags=re.I)]


class Userform(tornado.web.RequestHandler):
        def get(self):
                self.finish("ok")


class Upload(tornado.web.RequestHandler):
    def post(self):
        fileinfo = self.request.files['filearg'][0]
        #print ("fileinfo is", fileinfo)
        fname = fileinfo['filename']
        extn = os.path.splitext(fname)[1]
        cname = str(uuid.uuid4()) + extn
        fh = open(__UPLOADS__ + cname, 'wb')
        fh.write(fileinfo['body'])
        fh.close()
        cf = CompareFaces()
        results = cf.test_image(fh.name, 0.6, True)
        os.remove(fh.name)
        self.finish(repr(results))


application = tornado.web.Application([
        (r"/", Userform),
        (r"/upload", Upload),
        ], debug=True)


if __name__ == "__main__":
    application.listen(8888)
    tornado.ioloop.IOLoop.instance().start()