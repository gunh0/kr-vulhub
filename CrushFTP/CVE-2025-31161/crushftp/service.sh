#!/usr/bin/env bash

CRUSH_DIR="/var/opt/crushftp"
SERVICE_LOG=$CRUSH_DIR/service.log

log() {
    echo "$(date '+%FT%T%z') $@" >> $SERVICE_LOG
}

log_n() {
    echo -n "$(date '+%FT%T%z') $@" >> $SERVICE_LOG
}

COMMAND_ARGS="$@"
log "Service execute: '$COMMAND_ARGS'"

get_pid() {
    unset CRUSH_PID
    CRUSH_PID="`ps -a | grep "java" | grep "$CRUSH_DIR/CrushFTP.jar" | awk '{print $1}'`"
}

if [[ "$COMMAND_ARGS" == "crushftp restart" ]] ; then
    log "[Restart] Stopping..."
    get_pid
    #echo "CRUSH_PID:  $CRUSH_PID"
    if [[ -z "$CRUSH_PID" ]] ; then    
        log "Cannot find PID"
        exit 1
    fi

    log_n "[Restart] Shutting down CrushFTP... "
    kill $CRUSH_PID
    ret_val=$?
    if [ ${ret_val} -ne 0 ] ; then
        echo FAIL
        log "Could not kill PID"
        exit 1
    fi
    echo OK >> $SERVICE_LOG

    log_n "[Restart] Starting CrushFTP... "
    java -Xmx512m -jar $CRUSH_DIR/CrushFTP.jar -d & >/dev/null 2>&1
    echo OK >> $SERVICE_LOG

    exit 0
fi
