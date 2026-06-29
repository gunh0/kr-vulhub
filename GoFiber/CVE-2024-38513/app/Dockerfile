FROM golang:1.21-alpine AS builder

WORKDIR /build

COPY go.mod go.sum ./
RUN go mod download

COPY main.go ./
RUN go build -o server .

FROM alpine:3.18

WORKDIR /app
COPY --from=builder /build/server .

EXPOSE 3000
CMD ["./server"]
