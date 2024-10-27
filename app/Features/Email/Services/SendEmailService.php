<?php

declare(strict_types=1);

namespace App\Features\Email\Services;

use App\Features\Email\Dtos\SendEmailDto;
use App\Features\Shared\Helpers\Exceptions\ExceptionFormatter;
use Illuminate\Support\Facades\Log;
use SendGrid\Mail\Mail;
use SendGrid\Mail\TypeException;
use SendGrid\Response;
use Throwable;

class SendEmailService
{
    /**
     * @throws Throwable
     */
    public function sendEmail(SendEmailDto $emailDto): void
    {
        $email = $this->buildMessage($emailDto);
        $sendgrid = new \SendGrid(config('sendgrid.key'));

        try {
            $response = $sendgrid->send($email);
            $this->logResponse($emailDto, $response);
        } catch (\Exception $e) {
            Log::error('error-send-email', $e);
        }
    }

    private function getResponseBody(Response $response): array|string
    {
        if (json_validate($response->body())) {
            return json_decode($response->body(), true);
        }

        return $response->body();
    }

    /**
     * @throws Throwable
     * @throws TypeException
     */
    private function buildMessage(SendEmailDto $emailDto): Mail
    {
        $email = new Mail();
        $email->setFrom(config('mail.from.address'), config('mail.from.name'));
        $email->setSubject($emailDto->body->envelope()->subject);
        $email->addTo($emailDto->to->value, $emailDto->name);
        $view = view('user.mail.confirm_email', $emailDto->body->content()->with);
        $email->addContent('text/html', $view->render());

        return $email;
    }

    private function logResponse(SendEmailDto $emailDto, Response $response): void
    {
        Log::info('send-email-response', [
            'to' => $emailDto->to->value,
            'to_name' => $emailDto->name,
            'subject' => $emailDto->body->envelope()->subject,
            'from' => config('mail.from.address'),
            'response' => $response->statusCode(),
            'body' => $this->getResponseBody($response),
        ]);
    }
}
