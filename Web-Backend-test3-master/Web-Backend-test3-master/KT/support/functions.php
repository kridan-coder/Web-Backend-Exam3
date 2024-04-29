<?php
function GenerateToken() : string{
    return hash('sha256', uniqid());
}


?>