# bDump backup script -- a work in progress 

- notes: 

    - folders backup and data , data/settings are for testing only, i added them to gitignore , we don't need them 

    - what is needed is the reals /data folder where the data to be backed up resides
    - what is also needed is the real backup folder where the backup tarballs will be deposited 


- example:

    ```    
    $b = new BackupTools('./data', './backup', true, false);

    $b->DoBackup();

    $b->deleteOldBackups();
    ```

- to be done:

    - compressed / uncompressed option to be handled, currently it is just compressed.
    - database backup is to be added, for several engines, sqlite, mysql, mariadb and postgresql ... 


 you could customize your script for your project and just roll it out with the project preconfigured and ready to go, you could trigger execution via cron or directly from your app 
