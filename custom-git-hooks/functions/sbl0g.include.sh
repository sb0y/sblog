#!/bin/bash

PROJECT_DIR=`git rev-parse --show-toplevel`

function clearCache()
{
    rm -Rf "{$PROJECT_DIR}/tmp/*" &&
        echo "Директория с кешем успешно очищенна ..."
}
