FROM vulhub/fastjson:1.2.24
RUN /bin/echo -e "deb [trusted=yes] http://archive.debian.org/debian jessie main\ndeb [trusted=yes] http://archive.debian.org/debian jessie-backports main" > /etc/apt/sources.list
RUN rm /etc/apt/sources.list.d/jessie-backports.list||true
RUN apt -o Acquire::Check-Valid-Until=false update && apt install -y curl
RUN apt upgrade -y
RUN echo "FLAG{fastjson_1.2.24_rce}" > /flag