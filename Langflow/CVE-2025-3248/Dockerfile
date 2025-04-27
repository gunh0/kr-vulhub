FROM python:3.10-slim

RUN apt-get update && apt-get install -y \
    git \
    && apt-get clean

RUN pip install langflow==1.2.0

EXPOSE 7860

CMD ["langflow", "run", "--host", "0.0.0.0", "--port", "7860"]
