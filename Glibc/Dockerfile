FROM debian:bookworm-20230919-slim@sha256:24c92a69df28b21676d721fe18c0bf64138bfc69b486746ad935b49cc31b0b91

# Do not run apt-get upgrade here.  This dated image intentionally contains the
# vulnerable glibc package used by this isolated CVE lab.
COPY --chmod=0555 poc.sh /lab/poc.sh
COPY --chmod=0555 verify.sh /lab/verify.sh

USER nobody
WORKDIR /lab
ENTRYPOINT ["/lab/verify.sh"]
