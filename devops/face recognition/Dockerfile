FROM i386/ubuntu:16.04

# Repository/Image Maintainer
LABEL maintainer="Pedro Malta <pedromalta@gmail.com>"

RUN echo "---> Updating Ubuntu" && \
    apt-get update && apt-get -y upgrade

RUN echo "---> Installing Build Requirements" && \
    apt-get install -y build-essential cmake cmake-curses-gui wget unzip

RUN echo "---> Cleaning up apt lists" && \
    rm -rf /var/lib/apt/lists/*

RUN echo "---> Get OpenCV 2.4.11" && \
    wget -O opencv-2.4.11.zip http://sourceforge.net/projects/opencvlibrary/files/opencv-unix/2.4.11/opencv-2.4.11.zip/download && \
    unzip opencv-2.4.11.zip

RUN echo "---> Build OpenCV" && \
    cd opencv-2.4.11  && \
    mkdir build && \
    cd build && \
    cmake -DCMAKE_BUILD_TYPE=Release .. && \
    make -j4 && \
    make install && \
    cd ../.. && \
    rm -rf opencv-2.4.11*

# Add files.
#ADD root/.bashrc /root/.bashrc
#ADD root/.gitconfig /root/.gitconfig
#ADD root/.scripts /root/.scripts

# Set environment variables.
ENV HOME /root

# Define working directory.
WORKDIR /root

# As non daemon and single base image, it may be used as cli container
CMD ["/bin/bash"]