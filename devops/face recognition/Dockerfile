FROM i386/ubuntu:16.04

# Repository/Image Maintainer
LABEL maintainer="Pedro Malta <pedromalta@gmail.com>"

RUN echo "---> Updating Ubuntu" && \
    apt-get update && apt-get -y upgrade

RUN echo "---> Installing Build Requirements" && \
    apt-get install -y build-essential cmake cmake-curses-gui wget unzip git

RUN echo "---> Cleaning up apt lists" && \
    rm -rf /var/lib/apt/lists/*

RUN echo "---> Get OpenCV 2.4.11" && \
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
