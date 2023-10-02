#!/bin/bash

# migrate.sh - Runs migrations nightly for Travel Forms
#              Should be run from drupal cron once every night and will send
#              any found issues to email list
#
# Originally created by Jason Overstreet 2020-11-30

##################################################################
# Set Variables for the environment
##################################################################

#Set width for prettier output
export COLUMNS=132

MIGRATELOGDIR="/public/server/logs/migrate"
RUNNINGLOGFILE="/public/server/logs/migrate/migrate.log"
ERRORSTRING="   0       0          0    "
DIRDATE="`date '+%Y%m%d'`"

#How many days of error logs to keep
KEEP_DAILY_X_DAYS=90

EMAIL_NOTIFICATION="potter.bryan@epa.gov,Marruffo.Json@epa.gov,Garnes.Sylvette@epa.gov,Dearie.Jessica@epa.gov,ryan.letulle@cgifederal.com,Cerda.Jeremy@epa.gov"
ALERT_NOTIFICATION="potter.bryan@epa.gov,Marruffo.Json@epa.gov,Garnes.Sylvette@epa.gov,Dearie.Jessica@epa.gov,ryan.letulle@cgifederal.com,Cerda.Jeremy@epa.gov"

DRUSHCMD="/usr/bin/drush @intra"

#Enter a list of sources that will be migrated SOURCELIST="qapp sop all_windows_shares_site_codes all_windows_shares"
#Enter items that need to be run a second time SECONDSOURCELIST="qapp sop"
ROLLBACKLIST="qapp sop"
##################################################################
# Check
##################################################################

echo "migrate.sh started at "`date` > ${MIGRATELOGDIR}/startchk.$$ cat ${MIGRATELOGDIR}/startchk.$$ echo ""

echo "" >> ${MIGRATELOGDIR}/migrate${DIRDATE}
echo "--------------------" >> ${MIGRATELOGDIR}/migrate${DIRDATE}
echo "migrate.sh started at "`date` >> ${MIGRATELOGDIR}/migrate${DIRDATE}
echo "" >> ${MIGRATELOGDIR}/migrate${DIRDATE}

#Create directory if it doesn't exist
if [ ! -d ${MIGRATELOGDIR} ]
then
  mkdir -p ${MIGRATELOGDIR};
fi

#Create file if it doesn't exist
if [ ! -f ${MIGRATELOGDIR}/migrate${DIRDATE} ] then
  touch ${MIGRATELOGDIR}/migrate${DIRDATE}
fi

#For each SOURCE, get the status ahead of time for SOURCEITEM in ${SOURCELIST} do
  echo "" >> ${MIGRATELOGDIR}/migrate${DIRDATE}
  echo "Retrieving status for ${SOURCEITEM} `date` " >> ${MIGRATELOGDIR}/migrate${DIRDATE}
  ${DRUSHCMD} migrate-status ${SOURCEITEM} >> ${MIGRATELOGDIR}/migrate${DIRDATE}
done

#For each SOURCE, run the rollback
for ROLLBACKITEM in ${ROLLBACKLIST}
do
  echo "" >> ${MIGRATELOGDIR}/migrate${DIRDATE}
  echo "Running rollback for ${ROLLBACKITEM} `date` " >> ${MIGRATELOGDIR}/migrate${DIRDATE}
  ${DRUSHCMD} migrate-rollback ${ROLLBACKITEM} >> ${MIGRATELOGDIR}/migrate${DIRDATE}
done

#For each SOURCE, run the import
for SOURCEITEM in ${SOURCELIST}
do
  echo "" >> ${MIGRATELOGDIR}/migrate${DIRDATE}
  echo "Running import for ${SOURCEITEM} `date` " >> ${MIGRATELOGDIR}/migrate${DIRDATE}
  ${DRUSHCMD} migrate-import ${SOURCEITEM} >> ${MIGRATELOGDIR}/migrate${DIRDATE}
done

#Having issues with some of the imports, so running again, just in case for SOURCEITEM in ${SECONDSOURCELIST} do
  echo "" >> ${MIGRATELOGDIR}/migrate${DIRDATE}
  echo "Running second import for ${SOURCEITEM} `date` " >> ${MIGRATELOGDIR}/migrate${DIRDATE}
  ${DRUSHCMD} migrate-import ${SOURCEITEM} >> ${MIGRATELOGDIR}/migrate${DIRDATE}
done

#For each SOURCE, get the status afterwards for SOURCEITEM in ${SOURCELIST} do
  echo "" >> ${MIGRATELOGDIR}/migrate${DIRDATE}
  echo "Retrieving status for ${SOURCEITEM} `date` " >> ${MIGRATELOGDIR}/migrate${DIRDATE}
  ${DRUSHCMD} migrate-status ${SOURCEITEM} >> ${MIGRATELOGDIR}/migrate${DIRDATE}
done

#Send output
mail -s "Migration output from `uname -n` for ${DIRDATE}" ${EMAIL_NOTIFICATION} < ${MIGRATELOGDIR}/migrate${DIRDATE}

#check for errors
if [ `grep "${ERRORSTRING}" ${MIGRATELOGDIR}/migrate${DIRDATE}` ] then
  LINECHK=`cat startchk.$$`
  LOGSTART=`grep -n ${LINECHK} ${RUNNINGLOGFILE}`

  echo "ALERT! There may be an issue with the migrate script!" > ${MIGRATELOGDIR}/alert${DIRDATE}
  echo "" >> ${MIGRATELOGDIR}/alert${DIRDATE}
  echo "Output from "${DIRDATE} >> ${MIGRATELOGDIR}/alert${DIRDATE}
  echo "" >> ${MIGRATELOGDIR}/alert${DIRDATE}
  cat ${MIGRATELOGDIR}/migrate${DIRDATE} >> ${MIGRATELOGDIR}/alert${DIRDATE}
  echo "" >> ${MIGRATELOGDIR}/alert${DIRDATE}
  echo "This may be due to the following:" >> ${MIGRATELOGDIR}/alert${DIRDATE}
  tail -n +${LOGSTART} ${RUNNINGLOGFILE} >> ${MIGRATELOGDIR}/alert${DIRDATE}

  mail -s "**ALERT** Migration issue  from `uname -n` for ${DIRDATE}" ${ALERT_NOTIFICATION} < ${MIGRATELOGDIR}/alert${DIRDATE} fi



echo "migrate.sh finished at "`date`
echo "migrate.sh finished at "`date` >> ${MIGRATELOGDIR}/migrate${DIRDATE}


##################################################################
# Cleanup
##################################################################

echo "cleanup started at "`date`


rm -f ${MIGRATELOGDIR}/startchk.$$

#Find old daily files to remove them
for FILE in `find ${MIGRATELOGDIR} -name "migrate*gz" -mtime +${KEEP_DAILY_X_DAYS}` do
   echo "removing ${FILE} since it is older than ${KEEP_DAILY_X_DAYS} days."
   rm -f ${FILE}
done

for FILE in `find ${MIGRATELOGDIR} -name "alert*gz" -mtime +${KEEP_DAILY_X_DAYS}` do
   echo "removing ${FILE} since it is older than ${KEEP_DAILY_X_DAYS} days."
   rm -f ${FILE}
done

#Compress daily log file
for FILE in `find ${MIGRATELOGDIR} -name "migrate????????" -mtime +1 ` do
   gzip ${FILE}
done

for FILE in `find ${MIGRATELOGDIR} -name "alert????????" -mtime +1 ` do
   gzip ${FILE}
done
