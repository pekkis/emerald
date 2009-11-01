<?php
class Emerald_Ajax_DataSource_Wrapper
{
	protected $dataSet;
	protected $wrappedData = Array();
	protected $index = null;
	protected $count = null;
	
	public function __construct($dataSet)
	{
		$this->dataSet = $dataSet;
		
	}
	public function wrap()
	{	
		foreach($this->dataSet as $row)
		{
			$this->wrappedData[] =  (is_array($row))?$row:$row->toArray();
		}
	}
	public function get($dataStart, $dataEnd, $totalCount = 0)
	{
	
		$this->wrap();	
		$this->index = (int)$dataStart;
		$this->count = count($this->wrappedData);
	
		return Array
		(
			"indexStart" => $this->index,
			"indexEnd" => $this->index + $this->count - 1,
			"count" => $this->count,
			"total" => $totalCount,
			"data" => $this->wrappedData
		);
	}
}