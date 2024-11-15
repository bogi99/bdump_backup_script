<?php

trait PrintoutTrait
{

    private function printout($text): void
    {
        if (is_array($text)) {
            foreach ($text as $line) {
                echo PHP_EOL . $line . PHP_EOL;
            }
        } else {
            echo PHP_EOL . $text . PHP_EOL;
        }
    }
}


trait TerminalColor
{

    function color($val)
    {
        $col['default'] = "\e[39m";
        $col['black'] = "\e[30m";
        $col['red'] = "\e[31m";

        $col['green'] = "\e[32m";
        $col['yellow'] = "\e[33m";
        $col['blue'] = "\e[34m";

        $col['magenta'] = "\e[35m";
        $col['cyan'] = "\e[36m";
        $col['lightgray'] = "\e[37m";

        $col['darkgray'] = "\e[90m";
        $col['brightred'] = "\e[91m";
        $col['brightgreen'] = "\e[92m";

        $col['brightyellow'] = "\e[93m";
        $col['brightblue'] = "\e[94m";
        $col['brightmagenta'] = "\e[95m";

        $col['brightsyan'] = "\e[96m";
        $col['white'] = "\e[97m";

        

        if (array_key_exists($val, $col)) {
            return $col[$val];
        } else {
            return $col['default'];
        }
    }
}

class BackupTools
{

    use PrintoutTrait;
    use TerminalColor;

    private $sourceDir, $destDir;
    private $compressed, $db;
    public function __construct(string $sourceDir, string $destDir, bool $compressed = false, bool $db = false)
    {
        $this->sourceDir = $sourceDir;
        $this->destDir = $destDir;

        $this->compressed = $compressed;
        $this->db = $db;

        $defaultColor = $this->color('default');
        $green = $this->color('green');

        $defaultMessage[] = $green . " Backup Tools " . $defaultColor;
        $defaultMessage[] = "Parameter list: ";
        $defaultMessage[] = " source dir : where the source of the backup resides. it can't be blank and it can't be the same as the destination ";
        $defaultMessage[] = " destination dir : where the backup tarball will be created. it can't be empty and it can't be the same as source dir ";



        $this->printout($defaultMessage);
        // $this->color('red');
    }



    public function DoBackup()
    {
        if ($this->testInputPars() === false) {
            die($this->printout(" Error Input parameters incorrect "));
        }

        //$command = "tar --exclude='./.*' -czf $this->sourceDir  -C $this->destDir .";
        $command = "tar -czf $this->destDir"."/".$this->formulateBackupFilename()."  -C $this->sourceDir . ";
        exec($command , $output, $return_var);

        $this->printout($command);
        $this->printout($output);
        $this->printout($return_var);
        $this->printout($this->formulateBackupFilename());
    }

    private function formulateBackupFilename()
    {
        $retval = "project_at".str_replace( '/','_', __DIR__ ."/").date('Y-m-d_H-i-s').".tar.gz";        
        return $retval ;
    } 

    public function deleteOldBackups(int $daysOld = 30)
    {
        $oldtimestamp = time() - ($daysOld * 24 * 60 * 60);
        $files = glob($this->destDir."/*");

        foreach ($files as $file )
        {
            if(is_file($file) && filemtime($file) < $oldtimestamp) ;
            // unlink($file);
            $this->printout("deleted ".$file."it was too old");
        }

    }

    private function testInputPars(): bool
    {
        // Do some sanity checks on the provided parameters 
        $retval = false;

        if ($this->sourceDir !== '' && $this->destDir !== '') {
            $retval = true;
        }
        if ($this->sourceDir == $this->destDir) {
            $retval = false;
        }




        return true;
    }
}


// exampple of how to call it 

$b = new BackupTools('./data', './backup', true, false);

$b->DoBackup();

$b->deleteOldBackups();

