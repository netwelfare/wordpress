<?php

class SimpleHttpClientException extends Exception
{
}

class SimpleHttpClient extends SimpleHttpClientException
{
    private $ch;
    private $method;
    private $headers = array();
    private $options = array();
    private $url;
    private $result;
    private $error;
    private $info;
    private $body;

    /**
     * @return resource
     * @throws Exception
     */
    public function init()
    {
        $this->ch = curl_init();
        if ($this->ch === false) {
            throw new Exception("curl init error");
        }
        return $this->ch;
    }

    /**
     * @param $header
     * @param null $value
     * @return $this
     */
    public function header($header, $value = null)
    {
        if (is_array($header)) {
            $this->headers = array_merge($this->headers, $header);
        } elseif ($value != null) {
            $this->headers[$header] = $value;
        } else {
            unset($this->headers[$header]);
        }
        return $this;
    }

    /**
     * @param $url
     * @return $this
     */
    public function url($url)
    {
        $this->url = $url;
        if (substr($url, 0, 8) === 'https://') {
            $this->https();
        }
        return $this;
    }

    /**
     * @param $method
     * @return $this
     */
    public function method($method)
    {
        $this->method = $method;
        return $this;
    }

    public function options($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param $body
     * @return $this
     */
    public function body($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * 关闭链接
     */
    public function close()
    {
        curl_close($this->ch);
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * 提交链接
     * @param $option
     * @return $this
     */
    public function send($option)
    {
        $options = array(
            CURLOPT_URL => $this->url,
        );
        $options[CURLOPT_HTTPHEADER] = $this->headers;

        if ($this->method == 'GET') {
            $options[CURLOPT_HTTPGET] = true;
        } elseif ($this->method == 'HEAD') {
            $options[CURLOPT_HTTPGET] = true;
            $options[CURLOPT_NOBODY] = true;
        } else {
            if ($this->method == 'POST') {
                $options[CURLOPT_POST] = true;
            } else {
                $options[CURLOPT_CUSTOMREQUEST] = $this->method;
            }
            $options[CURLOPT_POSTFIELDS] = $this->body;
            $headers = array();
            foreach ($this->headers as $key => $value) {
                $headers[] = "$key: $value";
            }
            $headers[] = 'Expect:';
            $options[CURLOPT_HTTPHEADER] = $headers;
        }

        Baidu_common::map_array_merge($options, $option);
        Baidu_common::map_array_merge($options, $this->options);

        curl_setopt_array($this->ch, $options);
        $this->result = curl_exec($this->ch);
        return $this;
    }

    /**
     * 获取返回结果
     * @return mixed
     */
    public function result()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function error()
    {
        if (!$this->error) {
            return $this->error = curl_error($this->ch);
        } else {
            return $this->error;
        }
    }

    public function info()
    {
        if (!$this->info) {
            return $this->info = curl_getinfo($this->ch);
        } else {
            return $this->info;
        }
    }
}
