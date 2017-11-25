<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Framework\Controller;

use App\Data\Recipe;
use App\Domain\AppClient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RecipeSearchController extends Controller
{
    /*
     *  @var AppClient $appClient 
     */
    private $appClient;

    /**
     * RecipeSearchController constructor.
     * @param AppClient $appClient
     * @param string $domain
     */
    public function __construct(AppClient $appClient, string $domain)
    {
        $this->appClient = $appClient;
        $this->appClient->setDomain($domain);
        $this->appClient->initClient();
    }
  
    /**
     * @return Client
     */
    public function getAppClient()
    {
      return $this->appClient;
    }  

    /**
     * @param Client $appClient
     */
    public function setAppClient(AppClient $appClient)
    {
      $this->appClient = $appClient;
    }
  
    /**
     * @Route("/api/recipe_search/i={tags}/q={search}/p={page}", name="recipe_search")
     * @param string $tags
     * @param string $search
     * @param int $page
     * @return JsonResponse
     */
    public function searchAction($tags = "", $search = "", $page = 1)
    {
        $requestResponse = $this->getAppClient()->request($tags, $search, $page);
        $arrayResponse = \GuzzleHttp\json_decode($requestResponse, true);
        $jsonResponse = [];
        if (is_array($arrayResponse) && !empty($arrayResponse["results"])) {
            foreach ($arrayResponse["results"] as $recipe) {
                $recipe = new Recipe($recipe);
                $jsonResponse[] = [
                    "title" => $recipe->getTitle(),
                    "href" => $recipe->getHref(),
                    "ingredients" => $recipe->getIngredients(),
                    "thumbnail" => $recipe->getThumbnail(),
                ];
            }
        } else {
            $jsonResponse = [];
        }

        return new JsonResponse($jsonResponse);        
  }
}

