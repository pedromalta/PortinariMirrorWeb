FROM i386/ubuntu:16.04

# Repository/Image Maintainer
LABEL maintainer="Pedro Malta <pedromalta@gmail.com>"

RUN echo "---> Updating Ubuntu" && \
    apt-get update && apt-get -y upgrade

RUN echo "---> Installing Build Requirements" && \
    apt-get install -y build-essential cmake cmake-curses-gui wget unzip git

RUN echo "---> Cleaning up apt lists" && \
    rm -rf /var/lib/apt/lists/*

RUN echo "---> Get OpenCV" && \
    git clone https://github.com/opencv/opencv.git && \
    git clone https://github.com/opencv/opencv_contrib.git

RUN echo "---> Build OpenCV" && \
    cd opencv  && \
    mkdir build && \
    cd build && \
    cmake -DCMAKE_BUILD_TYPE=Release .. && \
    # -D ENABLE_PRECOMPILED_HEADERS=OFF .. && \
    make -j4 && \
    make install && \
    cd ../.. && \
    rm -rf opencv*

RUN echo "---> Get OpenBR" && \
    git clone https://github.com/biometrics/openbr.git && \
    cd openbr && \
    git checkout v1.1.0 && \
    git submodule init && \
    git submodule update

RUN echo "---> Build OpenBR" && \
    mkdir build && \
    cd build && \
    cmake -DCMAKE_BUILD_TYPE=Release .. && \
    make -j4 && \
    sudo make install

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
