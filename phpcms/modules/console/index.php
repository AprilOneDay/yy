<?php
class index
{
    public function guahao()
    {
        header('LOCATION:/frame/index.php?m=console&c=orders&a=list_doctor');
    }

    public function room()
    {
        header('LOCATION:/frame/index.php?m=console&c=orders&a=list_room');
    }
}
