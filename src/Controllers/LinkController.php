<?php

namespace App\Controllers;

use App\Models\Link;
use App\Services\LinkService;

class LinkController
{
    private LinkService $linkService;

    public function __construct()
    {
        $this->linkService = new LinkService();
    }

    public function create(): void
    {
        session_start();
        $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);

        if (empty($url)) {
            $_SESSION['error'] = 'Invalid or empty URL.';
            header('Location: /');
            exit;
        }

        $urlShort = substr(hash('sha256', uniqid('', true)), 0, 8);

        $urlModel = new Link($urlShort, $url);

        $result = $this->linkService->createLink($urlModel);

        if ($result['success']) {
            $_SESSION['success'] = 'Link successfully created!';
            $_SESSION['shortenedUrl'] = $urlShort;

            header('Location: /');
            exit;
        } else {
            $_SESSION['error'] = $result['message'];
            header('Location: /');
            exit;
        }
    }

    public function showId(string $id): void
    {
        $link = $this->linkService->getLinkById($id);

        if ($link) {
            $url = $link->getUrl();
            require __DIR__ . '/../Views/redirect.php';
        } else {
            require __DIR__ . '/../Views/notFound.php';
            exit;
        }
    }
}
