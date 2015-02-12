<?php

if ($issetToken) {
	APISecurity::updateByToken($conn, $token);
}
