<?php

if ($issetToken) {
	FHQSecurity::updateByToken($conn, $token);
}
