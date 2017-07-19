<?php
class Task{

	protected $TaskId;
	protected $coroutine;
	protected $sendValue = null;
	protected $beforeFirstYied = true;



	public function __construct($TaskId, Generator $coroutine){
		$this->taskId = $TaskId;
		$this->coroutine = $coroutine;
	}

	public function getTaskId(){
		return $this->taskId;
	}

	public function setSendValue(){
		$this->sendValue = $sendValue;
	}

	public function run(){
		if ($this->beforeFirstYied) {
			$this->beforeFirstYied = false;
			return $this->coroutine->current();
		}else{
			$ret= $this->coroutine->send($this->sendValue);
			$this->sendValue = null;
			return $ret;
		}
	}

	public function finished(){
		return !$this->coroutine->valid();
	}
}
