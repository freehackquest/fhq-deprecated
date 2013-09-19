<?
        include_once('config/config.php');

        $nfs_share = $config['nfs_share'];

        echo '<pre>Test NFS'."\n";
        echo "nfs_share: ".$config['nfs_share']."\n";
        echo 'put content'."\n";
        $test_content = "test PHP222";
        file_put_contents($nfs_share."/2.txt", $test_content);

        {
                $output = "";
                echo exec('whoami');
                echo "\n\n";
                echo exec('touch /mnt/create_users/4.txt', $output);
                print_r($output);
                echo "\n\n";
        }

        if($handle = opendir($nfs_share)) {
        //if($handle = opendir('93.91.166.5:/home/nfs')) {
                echo "Discriptor directory: $handle\n";
                echo "Records: \n";
                while(false !== ($entry = readdir($handle)))
                {
                        echo "$entry\n";
                };
                closedir($handle);
        }
        else
        {
                echo "Could not read dir\n";
        }
        file_put_content('/mnt/');
?>
