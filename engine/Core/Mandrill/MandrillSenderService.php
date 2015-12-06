<?php
namespace Core\Mandrill;

/**
 * Class MandrillSenderService
 * @package Core\Mandrill
 */
class MandrillSenderService {

    /**
     * @var \Mandrill
     */
    protected $mandrillService;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $replyTo;

    /**
     * @var string
     */
    protected $from;

    /**
     * @param \Mandrill $mandrillService
     * @param string $host
     * @param string $replyTo
     * @param string $from
     */
    public function __construct(
        \Mandrill $mandrillService,
        $host,
        $replyTo,
        $from
    ) {
        $this->mandrillService = $mandrillService;
        $this->host = $host;
        $this->replyTo = $replyTo;
        $this->from = $from;
    }

    /**
     * @param array $message
     * @param bool $async
     * @param null $ip_pool
     * @param null $send_at
     * @return array
     */
    public function send(array $message, $async=false, $ip_pool=null, $send_at=null)
    {
        return $this->mandrillService->messages->send(
            $this->prepareMessage($message),
            $async, $ip_pool, $send_at
        );
    }

    /**
     * @param $templateName
     * @param array $templateContent
     * @param array $message
     * @param bool $async
     * @param null $ip_pool
     * @param null $send_at
     * @return array
     */
    public function sendTemplate(
        $templateName, array $templateContent, array $message, $async=false, $ip_pool=null, $send_at=null
    ) {
        return $this->mandrillService->messages->sendTemplate(
            $templateName,
            $templateContent,
            $this->prepareMessage($message),
            $async, $ip_pool, $send_at
        );
    }

    /**
     * @param array $message
     * @return array
     */
    protected function prepareMessage(array $message)
    {
        if(!isset($message['from_email'])) {
            $message['from_email'] = $this->from;
        }

        if(!isset($message['headers']['Reply-To'])) {
            $message['headers']['Reply-To'] = $this->replyTo;
        }

        //Replace host in text message if exist
        if(isset($message['text']) && strstr($message['text'], "{host}")) {
            $message['text'] = str_replace("{host}", $this->host, $message['text']);
        }

        //Replace host in html message if exist
        if(isset($message['html']) && strstr($message['html'], "{host}")) {
            $message['html'] = str_replace("{host}", $this->host, $message['html']);
        }

        if(!isset($message['global_merge_vars']['host'])) {
            $message['global_merge_vars'][] = ['name' => "host", 'content' => $this->host];
        }

        return $message;
    }



}