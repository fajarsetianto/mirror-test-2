<?php

namespace App\Jobs;

use App\Models\EducationalInstitution;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\Log;

class SyncEducationalInsitutions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $_client;
    protected $_token;    

    protected $_baseUri = 'https://api.vokasi.kemdikbud.go.id';
    protected $_username = 'emonev@api';
    protected $_password = 'b,]ZLJ7\]MyMC$mr@!tr';
    // protected $_password = 'b,]ZLJ7\]MyMC$mr@!t';
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_client = new Client([
            'base_uri' => $this->_baseUri
        ]);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->login()){
            $this->sync();
        }
    }

    protected function login(){
        $tries = 0;
        while($tries <= 5){
            $request = new Request('POST','auth/login', ['Content-Type' => 'application/x-www-form-urlencoded'], http_build_query([
                'username' => $this->_username, 
                'password' => $this->_password
            ]));
            
            $response = $this->_fetch($request);
            if($response != null){
                if($response->status == 200){
                    $this->_token = $response->data->token;
                    return true;
                    break;
                }
            }
            Log::warning('API data not contanin status 200', (array) $response);
            $tries++;
        }
        return false;
    }

    protected function sync(){
        $currentPage = 1;
        $pages = 1;
        while($currentPage <= $pages){
            sleep(5);
            $request = new Request(
                'POST',
                'data/spg?access-token='.$this->_token,
                ['Content-Type' => 'application/x-www-form-urlencoded'], 
                http_build_query([
                    'page' => $currentPage, 
                    'limit' => 30
                ])
            );
            $response = $this->_fetch($request);
            if($response != null){
                $pages = $response->total_page;
                $currentPage++;
                foreach($response->items as $item){
                    $newItem = collect($item)->except([
                        'nm_sp','alamat_jalan','nama_kabupaten','nama_provinsi','kode_pos','nomor_telepon','nomor_fax'
                    ])->toArray();
                    $newItem['name'] = $item->nm_sp;
                    $newItem['address'] = $item->alamat_jalan;
                    $newItem['city'] = $item->nama_kabupaten;
                    $newItem['province'] = $item->nama_provinsi;
                    $newItem['postal_code'] = $item->kode_pos;
                    $newItem['phone'] = $item->nomor_telepon;
                    $newItem['fax'] = $item->nomor_fax;
                    
                    EducationalInstitution::updateOrCreate(['sp_id' => $item->sp_id], $newItem);
                }
            }
        }
    }

    protected function _fetch(Request $request){
        try {
            $response = $this->_client->send($request,['version' => 1.2]);
            if($response->getStatusCode() == "200"){
                $responseData = json_decode($response->getBody());
                return $responseData;
            }else{
                Log::warning('API endpoint not return 200 status code' , (array) $response);
            }
        } catch (RequestException $e) {
            Log::warning(Psr7\Message::toString($e->getRequest()));
            if ($e->hasResponse()) {
                Log::warning(Psr7\Message::toString($e->getResponse()));
            }
        }
        return null;
    }

}
