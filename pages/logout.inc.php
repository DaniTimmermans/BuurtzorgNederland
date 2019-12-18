<?php
	session_start();
	session_destroy();
	session_reset();
	session_regenerate_id();
	header("Location:".HOME_PATH);