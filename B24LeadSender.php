<?php

namespace B24LeadSender;


class LeadSender
{
	private $queryUrl;
	private $queryData = array(
		"fields" => array(),
		"params" => array()
	);

	private $errorText;

	public function __construct($url, $userId)
	{
		$this->queryUrl = $url;
		$this->queryData["fields"]["ASSIGNED_BY_ID"] = $userId;
		$this->queryData["params"]["REGISTER_SONET_EVENT"] = "Y"; // отправить уведомление ответственному за лид
	}
	public function SetName($name)
	{
		$this->queryData["fields"]["NAME"] = $name;

		if (!strlen($this->queryData["fields"]["TITLE"])) {
			$this->queryData["fields"]["TITLE"] = "Новый лид: $name";
		}
	}
	public function SetTitle($title)
	{
		$this->queryData["fields"]["TITLE"] = $title;
	}

	public function AddPhone($tel)
	{
		if (!is_array($this->queryData["fields"]["PHONE"])) {
			$this->queryData["fields"]["PHONE"] = array();
		}
		$this->queryData["fields"]["PHONE"][] = array("VALUE" => $tel, "VALUE_TYPE" => "WORK");
	}
	public function AddEmail($email)
	{
		if (!is_array($this->queryData["fields"]["EMAIL"])) {
			$this->queryData["fields"]["EMAIL"] = array();
		}
		$this->queryData["fields"]["EMAIL"][] = array("VALUE" => $email, "VALUE_TYPE" => "WORK");
	}
	public function SetComments($msg)
	{
		$this->queryData["fields"]["COMMENTS"] = $msg;
	}
	public function Send()
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $this->queryUrl,
			CURLOPT_POSTFIELDS => http_build_query($this->queryData),
		));

		$result = curl_exec($curl);
		curl_close($curl);

		if ($result === false) {
			$this->errorText = "curl_exec has returned false";
			return false;
		}

		$result = json_decode($result, true);

		if (array_key_exists('error', $result)) {
			$this->errorText = "B24 has returned error: " . $result['error_description'];
			return false;
		}
		return true;
	}
	public function GetError()
	{
		return $this->errorText;
	}
}
