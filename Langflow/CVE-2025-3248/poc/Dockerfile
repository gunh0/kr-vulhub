FROM python:3.11-slim

WORKDIR /poc

RUN pip install requests

COPY poc.py .
COPY run_poc.sh .

RUN sed -i 's/\r$//' run_poc.sh && chmod +x run_poc.sh

CMD ["sh", "./run_poc.sh"]
