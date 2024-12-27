<?php

namespace App\Services;

use App\Models\Link;
use App\Database\MongoConnection;
use MongoDB\Collection;

class LinkService
{
    private Collection $collection;

    public function __construct()
    {
        // Obtem a conexão com o MongoDB
        $mongoConnection = new MongoConnection();
        $this->collection = $mongoConnection->getDatabase()->selectCollection('links'); // "links" é o nome da coleção no MongoDB
    }

    public function createLink(Link $link): array
    {
        try {
            // Verifica se o short_url já existe
            $existing = $this->collection->findOne(['_id' => $link->getId()]);

            if ($existing) {
                return [
                    'success' => false,
                    'message' => 'O link curto já está em uso.',
                ];
            }

            $this->collection->insertOne([
                '_id' => $link->getId(), // short_url_text como _id
                'url' => $link->getUrl(),
            ]);

            return [
                'success' => true,
                'message' => 'Link criado com sucesso.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao criar o link: ' . $e->getMessage(),
            ];
        }
    }

    public function getLinkById(string $shortUrl): ?Link
    {
        $data = $this->collection->findOne(['_id' => $shortUrl]);

        if ($data) {
            return new Link($data['_id'], $data['url']);
        }

        return null;
    }
}
