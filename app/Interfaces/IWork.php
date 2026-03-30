<?php

namespace App\Interfaces;

interface IWork
{
	const STATE_NOT_START = 0;
	const STATE_IN_PROCESS = 1;
	const STATE_COMPLETED = 2;
	const STATE_SENT = 3;
}
