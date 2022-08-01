<?php

file_put_contents('post.txt', json_encode($_POST));
file_put_contents('input.txt', file_get_contents('php://input'));
