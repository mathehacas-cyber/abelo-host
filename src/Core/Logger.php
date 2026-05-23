<?php declare(strict_types=1);

namespace App\Core;

class Logger
{
    public function __construct(
        private readonly string $dir
    ) {}

    public function info(string $message, array $context = []): void
    {
        $this->write('INFO', $message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->write('WARNING', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->write('ERROR', $message, $context);
    }

    private function write(string $level, string $message, array $context): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $file = $this->dir . '/app-' . date('Y-m-d') . '.log';
        $extra = $context ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '';

        file_put_contents(
            $file,
            "[{$timestamp}] {$level}: {$message}{$extra}\n",
            FILE_APPEND | LOCK_EX
        );
    }
}
