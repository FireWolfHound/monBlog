<?php

namespace App\Service;


class Pagination
{
    public function pagination($repo, $nbPost, $page){
        $limit = $nbPost;

        $start = $page * $limit - $limit;

        $total = count($repo->findAll());

        $pages = ceil($total / $limit);

        return [
            'posts' => $repo->findBy([], [], $limit, $start),
            'pages' => $pages,
        ];
    }

}