<?php

use Illuminate\Database\Seeder;
use Elasticsearch\ClientBuilder;
class PostSeeder extends Seeder
{
    protected $host;
    protected $params;

    protected $client;

    public function initializeESServer()
    {
        $this->host = 'localhost:9200';
        $this->params['index']='posts';
        $this->client = \Elasticsearch\ClientBuilder::create()
            ->setHosts([$this->host])
            ->setRetries(0)
            ->build();
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->initializeESServer();
        // first intialize the index 
        //$this->initializeIndex();
        
        // Search 
        $this->search('autem');
        exit;

        //\DB::statement("DELETE from posts");

        $faker = \Faker\Factory::create();

        $data = [];
        for ($i=0; $i < 10000; $i++) { 
        	$temp['title']=$faker->sentence(2);
	    	$temp['body']=$faker->paragraph(10);
            $temp['tags']=join(',', $faker->words(5));
	    	$temp['user_id']=rand(1,5);
	    	$temp['created_at']=date('Y-m-d H:i:s');
	    	$temp['updated_at']=date('Y-m-d H:i:s');
	    	$post = \App\Post::create($temp);

            $insert_params = [
                'index' => 'posts',
                'body'  => ['id'=>$post->id,'title' => $post->title,'body'=>$post->body,'tags'=>$post->tags]
            ];
            // Insert data in index
            $response = $this->client->index($insert_params);
        }
    }

    public function initializeIndex()
    {
        
        $params['index']='posts';
        $params['body'] = 
        [
            'settings' => [
                'number_of_shards' => 1,
                'number_of_replicas' => 1
            ],
            'mappings' => [
                '_source' => [
                    'enabled' => true
                ],
                'properties' => [
                    'id' => [
                      'type' => 'integer',
                    ],
                    'title' => [
                      'type' => 'text',
                      "analyzer" => "standard",
                    ],
                    'body' => [
                      'type' => 'text',
                      "analyzer" => "standard",
                    ],
                    'tags' => [
                      'type' => 'keyword'
                    ],
                ]
            ]
        ];

        return $this->client->indices()->create($params);
    }

    public function search($query)
    {
        $params = [
            'index' => 'posts',
            'body'  => [
                'query' => [
                    'match' => [
                        'title' => $query
                    ]
                ]
            ]
        ];

        $results = $this->client->search($params);
        echo json_encode($results);
        //echo json_encode($results['hits']['hits']);
    }

    public function clearDataInIndex()
    {
        $params = [
            'index' => 'posts',
            'body'  => [
                'query' => [
                    'match_all' => []
                ]
            ]
        ];

        $results = $this->client->deleteByQuery($params);
        echo json_encode($results);
    }
}

