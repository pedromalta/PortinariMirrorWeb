FROM i386/python:3.6-alpine3.7

# Repository/Image Maintainer
LABEL maintainer="Pedro Malta <pedromalta@gmail.com>"

RUN apk update \
    && apk add --no-cache git \
       cmake \
       g++ \
       make \
       jpeg jpeg-dev \
       libpng libpng-dev \
       giflib giflib-dev \
       lapack \
       bash \
       lapack-dev \
       ca-certificates \
       wget \
    && rm -rf /var/cache/apk/*

RUN pip install numpy

RUN cd ~ && \
    mkdir -p dlib && \
    git clone -b 'v19.9' --single-branch https://github.com/davisking/dlib.git dlib/ && \
    cd  dlib/ && \
    python3 setup.py install --no USE_AVX_INSTRUCTIONS --no DLIB_USE_CUDA

RUN pip install face_recognition
RUN pip install tornado

RUN mkdir /root/face-recognition
COPY bashrc /root/.bashrc
# Set environment variables.
ENV HOME /root
USER root

# Define working directory.
WORKDIR /root/face-recognition

# As non daemon and single base image, it may be used as cli container
CMD ["bash"]
