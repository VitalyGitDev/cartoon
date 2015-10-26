<?php
/*******************************************************************************
 *
 *
 *
 ***/
class axPost {

    var $url;
    var $url_segments;
    var $base;

    var $socket;
    var $request_headers;
    var $post_body;

    var $response_headers;
    var $response_code;
    var $content_type;
    var $content_length;
    var $response_body;

    function start_transfer($url)
    {
        $this->set_url($url);
        $this->open_socket();
        if ($this->socket) {
            $this->set_request_headers();
            $this->set_response();

            return $this->response_body;
        } else {
            return false;
        }
    }

    function open_socket()
    {
        $this->socket = @fsockopen($this->url_segments['host'], $this->url_segments['port'], $err_no, $err_str, 10);
        if ($err_str) {
            $this->socket = false;
        }
    }

    function set_response()
    {
        stream_set_timeout($this->socket, 10);
        fwrite($this->socket, $this->request_headers);
        $this->response_headers = '';
        $this->response_body = '';

        do
        {
            $line = fgets($this->socket, 4096);
            $this->response_headers .= $line;
        }
        while (
            !empty($line) && 
            $line != "\r\n");

        if (empty($this->response_headers)) {
            $this->active = false;
            //echo "Интерфейс недоступен\n";
            //exit();
        } else {
            //echo $this->response_headers;
        }

        $e = explode(' ', $this->response_headers);
        $this->response_code = next($e);
        $this->set_content_type();
        $this->set_content_length();

        do
        {
            $data = fread($this->socket, 8192);
            $this->response_body .= $data;

        }   
        while (strlen($data) != 0);
        
        // --------------------------------------------------------------------- NIK
        if ( strpos($this->response_body, '<?xml') !== false && strpos($this->response_body, '<?xml') > 0 ) {
		     $this->response_body = substr($this->response_body, strpos($this->response_body, '<?xml'));
		  }

        fclose($this->socket);
    }

    function set_content_type()
    {
        if (preg_match("#content-type:([^\r\n]*)#i", $this->response_headers, $matches) && trim($matches[1]) != '')
        {
            $content_type_array = explode(';', $matches[1]);
            $this->content_type = strtolower(trim($content_type_array[0]));
        }
        else
        {
            $this->content_type = 'text/html';
        }
    }

    function set_content_length()
    {
        if (preg_match("#content-length:([^\r\n]*)#i", $this->response_headers, $matches) && trim($matches[1]) != '')
        {
            $this->content_length = trim($matches[1]);
        }
        else
        {
            $this->content_length = false;
        }
    }

    function set_url($url)
    {
         $this->url = $url;

         if ($this->parse_url($this->url, $this->url_segments))
         {
             $this->base = $this->url_segments;
         }
    }

    function parse_url($url, & $container)
    {
        $temp = parse_url($url);
        
        if (!empty($temp))
        {
            $temp['port']     = isset($temp['port']) ? $temp['port'] : 80;
            $temp['path']     = isset($temp['path']) ? $temp['path'] : '/';
            $temp['file']     = substr($temp['path'], strrpos($temp['path'], '/')+1);
            $temp['dir']      = substr($temp['path'], 0, strrpos($temp['path'], '/'));
            $temp['base']     = $temp['scheme'] . '://' . $temp['host'] . ($temp['port'] != 80 ?  ':' . $temp['port'] : '') . $temp['dir'];
            $temp['prev_dir'] = $temp['path'] != '/' ? substr($temp['base'], 0, strrpos($temp['base'], '/')+1) : $temp['base'] . '/';
            $container = $temp;

            return true;
        }

        return false;
    }

    function set_request_headers()
    {
        $path = preg_replace('#/{2,}#', '/', $this->url_segments['path']);
        $path = preg_replace('#([^.]+)(\.\/)*#', '$1', $path);
        while ($path != ($path = preg_replace('#/[^/.]+/\.\./#', '/', $path)));
        $path = preg_replace('#^/(\.\./)*#', '/', $path);
        $path = preg_replace('#[^a-zA-Z0-9$\-_.+!*\'(),;/?:@=&]+#e', "'%'.dechex(ord('$0'))", $path);

        $headers  = "POST $path" . (isset($this->url_segments['query']) ? '?' . preg_replace('#[^a-zA-Z0-9$\-_.+!*\'(),;/?:@=&]+#e', "urlencode('$0')", urldecode($this->url_segments['query'])) : '') . " HTTP/1.0\r\n";
        $headers .= "Host: {$this->url_segments['host']}:{$this->url_segments['port']}\r\n";

        $headers .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $headers .= "Content-Length: " . strlen($this->post_body) . "\r\n\r\n";
        $headers .= $this->post_body;

        $headers .= "\r\n";

        $this->request_headers = $headers;
    }

    function set_post_body($array)
    {
        $array = $this->set_post_vars($array);
        foreach ($array as $key => $value)
        {
            $this->post_body .= !empty($this->post_body) ? '&' : '';
            $this->post_body .= $key . '=' . $value;
        }
    }

    function set_post_vars($array, $parent_key = null)
    {
        $tmp = array();

        foreach ($array as $key => $value)
        {
            $key = isset($parent_key) ? sprintf('%s[%s]', $parent_key, urlencode($key)) : urlencode($key);
            if (is_array($value))
            {
                $tmp = array_merge($tmp, $this->set_post_vars($value, $key));
            }
            else
            {
                $tmp[$key] = urlencode($value);
            }
        }
        return $tmp;
    }
}