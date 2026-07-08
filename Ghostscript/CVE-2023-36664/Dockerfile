FROM ubuntu:22.04

RUN apt update && \
    apt install -y \
    wget \
    build-essential \
    gedit \
    python3 \
    sudo && \
    rm -rf /var/lib/apt/lists/*
    

RUN wget https://github.com/ArtifexSoftware/ghostpdl-downloads/releases/download/gs10011/ghostscript-10.01.1.tar.gz && \
    tar -xzf ghostscript-10.01.1.tar.gz

WORKDIR /ghostscript-10.01.1

RUN ./configure && \
    make && \
    make install

RUN mkdir -p /var/www/html && \
    echo "DB_PASSWORD=SuperSecret1234!!!" > /var/www/html/config.php

RUN useradd -m -s /bin/bash test

WORKDIR /home/test
USER test
 
CMD ["/bin/bash"]



