<?php declare(strict_types=1);

namespace App\Core;

class Cache
{
    public function __construct(
        private readonly string $dir
    ) {}

    public function get(string $key): mixed
    {
        $file = $this->path($key);

        if (!file_exists($file)) {
            return null;
        }

        $data = unserialize(file_get_contents($file));

        if ($data['expires'] < time()) {
            unlink($file);
            return null;
        }

        return $data['value'];
    }

    public function set(string $key, mixed $value, int $ttl = 3600): void
    {
        file_put_contents(
            $this->path($key),
            serialize(['expires' => time() + $ttl, 'value' => $value]),
            LOCK_EX
        );
    }

    public function forget(string $key): void
    {
        $file = $this->path($key);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function remember(string $key, int $ttl, callable $callback): mixed
    {
        $cached = $this->get($key);
        if ($cached !== null) {
            return $cached;
        }

        $value = $callback();
        if ($value !== null) {
            $this->set($key, $value, $ttl);
        }

        return $value;
    }

    private function path(string $key): string
    {
        return $this->dir . '/' . md5($key) . '.cache';
    }
}
