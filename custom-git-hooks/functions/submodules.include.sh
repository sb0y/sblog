#!/bin/bash

submodules_init()
{
    cd ${PROJECT_DIR}
    git submodule init
    git submodule update

    echo "Подмодули репозитория инициализированны..."
}

submodules_pull()
{
    for i in `ls -1d "${PROJECT_DIR}/thirdparty"`;
    do
        cd "${PROJECT_DIR}/thirdparty/${i}"
        git pull origin master
        echo "Подмодуль \"${i}\" обновлён..."
    done
}
