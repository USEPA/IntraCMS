#!/bin/bash

# backup.sh - Create backups of a Drupal environment, including all files and database.
#             Requires drush, the drupal shell application (http://drupal.org/project/drush)


##################################################################
# Define common variables
##################################################################

        DIRDATE=`date '+%Y%m%d%H%M'`

        KEEP_DAILY_X_DAYS=30
        KEEP_WEEKLY_X_DAYS=90
        KEEP_MONTHLY_X_DAYS=750

        BACKUPDESTDIRROOT="/backups/data"
        LOGFILEDIR="/backups/logs"

        DRUSHDIR="C:\Users\JDearie\Sites\devdesktop\ORD\vendor\drush\drush"
        DRUSHOPT=""

        #List out any files or directories that should be backed up.
        #Put --exclude= in front if it should be excluded
        INCLUDES="
        config
        composer.json
        composer.lock
        docroot/sites/default/settings.php
        docroot/sites/default/settings.local.php
        --exclude=sites/*/files
        "

##################################################################
# Verify environment was given as parameter
##################################################################

if [ ! -d ${LOGFILEDIR} ]
then

  #Create log directory if it doesn't exist
  mkdir -p ${LOGFILEDIR}

  #Exit if directory path is not writable
  if [ $? -ne 0 ]
  then
    echo "ERROR! Couldn't create ${LOGFILEDIR}."
    exit
  fi
fi

echo "Output will be in ${LOGFILEDIR}/backup.log"
echo "--------------------" >> ${LOGFILEDIR}/backup.log
echo "backup.sh started at "`date` >> ${LOGFILEDIR}/backup.log
echo "backups placed in ${BACKUPDESTDIR}" >> ${LOGFILEDIR}/backup.log

#Check for drush application
if [ ! -f ${DRUSHDIR}/drush ]
then
  echo "ERROR! Drush is not located at ${DRUSHDIR}. Check DRUSHDIR parameter." >> ${LOGFILEDIR}/backup.log
  exit
fi

#DRUSHCHK=`${DRUSHDIR}/drush ${DRUSHOPT} st | grep "Database" | cut -f2 -d: | sed -e 's/^ *//' -e 's/ *$//'`
#Make sure parameter entered is valid and can connect to the database
#if [ ${DRUSHCHK} == "Connected" ]
#then
        #Strip the @ off the variable for the environment name
#        ENVIRONMENT=`echo ${DRUSHOPT} |  sed -e 's/^ *//' -e 's/ *$//' | cut -c2-`
#
#        BACKUPSOURCEDIR=`${DRUSHDIR}/drush ${DRUSHOPT} st | grep "Drupal root" | cut -f2 -d: | sed -e 's/^ *//' -e 's/ *$//'`
#        BACKUPDESTDIR=${BACKUPDESTDIRROOT}/${ENVIRONMENT}
#else
#        echo "Please provide a valid drush for DRUSHOPT (${DRUSHOPT})or check to make sure database is up "`date` >> ${LOGFILEDIR}/backup.log
#        exit
#fi
ENVIRONMENT='ord.local'
BACKUPSOURCEDIR='C:\Users\JDearie\Sites\devdesktop\ORD\docroot'

##################################################################
# Backup
##################################################################

#Need to go one level higher than docroot
cd ${BACKUPSOURCEDIR}/..

if [ ! -d ${BACKUPDESTDIR} ]
then

  #Create backup directory if it doesn't exist
  mkdir -p ${BACKUPDESTDIR};

  #Exit if directory path is not writable
  if [ $? -ne 0 ]
  then
    echo "ERROR! Couldn't create ${BACKUPDESTDIR} "`date` >> ${LOGFILEDIR}/backup.log
    exit
  fi

fi

#Set site to maintenance mode to prevent writes to db

MAINTCHECK=`${DRUSHDIR}/drush ${DRUSHOPT} sget system.maintenance`
if [ ${MAINTCHECK} ]
then
        echo "site is already in in maintenance mode "`date` >> ${LOGFILEDIR}/backup.log
else
        ${DRUSHDIR}/drush ${DRUSHOPT} sset system.maintenance 1
        echo "site put in maintenance mode "`date` >> ${LOGFILEDIR}/backup.log
fi

#Clear the cache
${DRUSHDIR}/drush ${DRUSHOPT} cr
echo "cache cleared" `date` >> ${LOGFILEDIR}/backup.log

#Get a list of differing configurations in case anything gets overwritten during imports
echo "The following changes are in the current database configuration and will be overwritten"
echo "by any changes pulled down from the repository. Environment configurations should be"
echo "done in development and pushed to the repository instead of being done on the end servers."
echo "Note: no changes are being done yet, but they potentially will be with the next git pull."
${DRUSHDIR}/drush ${DRUSHOPT} cex --diff --no

tar -czf ${BACKUPDESTDIR}/dailyenv${DIRDATE}.tgz ${INCLUDES}

echo "Environment files should be backed up into ${BACKUPDESTDIR}/dailyenv${DIRDATE}.tgz "`date` >> ${LOGFILEDIR}/backup.log

#Dump database files and compress them
${DRUSHDIR}/drush ${DRUSHOPT} sql-dump --result-file=${BACKUPDESTDIR}/dailymysqldump${DIRDATE}.sql --gzip

#Take site out of maintenance mode
if [ ${MAINTCHECK} ]
then
        echo "Keeping site in maintenance mode "`date` >> ${LOGFILEDIR}/backup.log
else
        echo "Taking site out of maintenance mode "`date` >> ${LOGFILEDIR}/backup.log
        ${DRUSHDIR}/drush ${DRUSHOPT} sset system.maintenance 0
fi

${DRUSHDIR}/drush ${DRUSHOPT} cr
echo "${ENVIRONMENT} cache cleared "`date` >> ${LOGFILEDIR}/backup.log

echo "Site online. Backup finished at "`date` >> ${LOGFILEDIR}/backup.log

##################################################################
# Cleanup
##################################################################

#Find old daily files to remove them
for FILE in `find ${BACKUPDESTDIR} -name "daily*" -ctime +${KEEP_DAILY_X_DAYS}`
do
   echo "removing ${FILE} since it is older than ${KEEP_DAILY_X_DAYS} days." >> ${LOGFILEDIR}/backup.log
   rm ${FILE}
done

#Make copies for weekly backups on Sunday
if [ `date +%w` = 0 ]
then
  echo "Calendar day of week: `date +%w`, creating weekly backups" >> ${LOGFILEDIR}/backup.log
  cp ${BACKUPDESTDIR}/dailyenv${DIRDATE}.tgz ${BACKUPDESTDIR}/weeklyenv${DIRDATE}.tgz
  cp ${BACKUPDESTDIR}/dailymysqldump${DIRDATE}.sql.gz ${BACKUPDESTDIR}/weeklymysqldump${DIRDATE}.sql.gz
fi

#Find old weekly files to remove them
for FILE in `find ${BACKUPDESTDIR} -name "weekly*" -ctime +${KEEP_WEEKLY_X_DAYS}`
do
   echo "removing ${FILE} since it is older than ${KEEP_WEEKLY_X_DAYS} days." >> ${LOGFILEDIR}/backup.log
   rm ${FILE}
done

#Make copies for monthly backups on the first of the month
if [ `date +%d` = 01 ]
then
  echo "Calendar date: `date +%d`, creating monthly backups" >> ${LOGFILEDIR}/backup.log
  cp ${BACKUPDESTDIR}/dailyenv${DIRDATE}.tgz ${BACKUPDESTDIR}/monthlyenv${DIRDATE}.tgz
  cp ${BACKUPDESTDIR}/dailymysqldump${DIRDATE}.sql.gz ${BACKUPDESTDIR}/monthlymysqldump${DIRDATE}.sql.gz

  #back up log file
  LASTMONTHLOGDATE=`date -d "1 day ago" '+%Y%m'`
  mv ${LOGFILEDIR}/backup.log ${LOGFILEDIR}/backup_${LASTMONTHLOGDATE}.log
  gzip  ${LOGFILEDIR}/backup_${LASTMONTHLOGDATE}.log
  echo "LOGFILE backed up to ${LOGFILEDIR}/backup_${LASTMONTHLOGDATE}.log.gz" >>  ${LOGFILEDIR}/backup.log

fi

#Find old monthly files to remove them
for FILE in `find ${BACKUPDESTDIR} -name "monthly*" -ctime +${KEEP_MONTHLY_X_DAYS}`
do
   echo "removing ${FILE} since it is older than ${KEEP_MONTHLY_X_DAYS} days." >> ${LOGFILEDIR}/backup.log
   rm ${FILE}
done

echo "cleanup finished at "`date` >> ${LOGFILEDIR}/backup.log
echo >> ${LOGFILEDIR}/backup.log
