<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Hash;
use Http;
use Illuminate\Http\Request;
use Log;
use GuzzleHttp\Client;

class ApiIntegrationController extends Controller
{
    protected string $baseUrl;
    protected string $api_key;
    protected string $password;

    public function __construct()
    {
        $this->baseUrl = 'http://api.ampera.radenfatah.ac.id';
        $this->api_key = 'b70a17ceb9';
    }

    public function getToken(): string
    {   
        try {
            $client = new Client([
            'verify' => false, // testing only
            ]);
            $response = $client->post(
                rtrim($this->baseUrl) . '/auth/gettoken',
                [
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
                        'Accept' => 'application/json',
                    ],

                    'form_params' => [
                        'api_key' => $this->api_key
                    ],

                    'curl' => [
                        CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                        CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1',
                    ],

                    'version' => 1.1,
                ]
            );

            $data = json_decode($response->getBody()->getContents(), true);
            
            return $data['token'];
        } catch (Exception $e) {
            Log::error('Error get token API: ' . $e->getMessage());

            throw $e;
        }
    }

    public function integration(Request $request, $page)
    {
        $requestData = $request->validate([
            'key' => 'required'
        ]);

        if ($requestData['key'] !== 'run-integration-v-1.0') {
            return response()->json(['error' => 'Invalid key'], 400);
        }
        // return response()->json(['error' => 'key ok'], 200);


        try {
            $token = $this->getToken();

            $client = new Client([
                'verify' => false, // testing only
            ]);

            $response = $client->get(
                rtrim($this->baseUrl, '/') . '/api/pegawai/filter/' . $page,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
                        'Accept' => 'application/json',
                    ],

                    'curl' => [
                        CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
                        CURLOPT_SSL_CIPHER_LIST => 'DEFAULT@SECLEVEL=1',
                    ],

                    'version' => 1.1,
                ]
            );

            $data = json_decode($response->getBody()->getContents(), true);

            $doens = $data['data'];
            $senats = [
                '195708011983031007',
                '196202141990031002',
                '197306041999031006',
                '197103042001121002',
                '197205252001121004',
                '197709172005011009',
                '196610011991032001',
                '196808211995031003',
                '197107271997032005',
                '196603281993031002',
                '196901241998031006',
                '197406122003121006',
                '197511102006042002',
                '197603232005011008',
                '197202012000031004',
                '197311102000031003',
                '197707152006042003',
                '197411152008011008',
                '197202202007102001',
                '198011232008012013',
                '196108061989031008',
                '196307121989032004',
                '195810291992031001',
                '196206201988031001',
                '196203051991011001',
                '196608071993021001',
                '196509271991031004',
                '196505191992031003',
                '197206291997032004',
                '197109111997031004',
                '197011261997032002',
                '197301021999032001',
                '197109011997032002',
                '197301251999031002',
                '196304131995031001',
                '197012081996031003',
                '197307131998031003',
                '197209011997032003',
                '197503192000032002',
                '196506112000031002',
                '197307112006041001',
                '197208242005012001',
                '197707032007102004',
                '197509282006042001',
                '197308141998032001',
                '197611052007102002',
                '196611121993031004',
                '196802072006042008',
                '198905142019032016',
                '196711071998032001',
                '199110022019031016',
                '196808292005011003',
                '197705252005011014',
                '197301142005012006',
                '198308032011011005',
                '199001052022031001',
                '196704131995032001',
                '199205222018012003',
                '197405122003121001',
                '197201221998032002',
                '197212131997032001',
                '196409301993031005',
                '198510162018011001',
                '197101211997031002',
                '199209112019031015',
            ];
            $kepegawaians = [
                "196710191994031002",
                "199612022019031002",
                "199305212020122022",
                "197303312001121001",
                "197612302009011009",
                "199709022025052006",
                "199511262025212014",
                "199603062022032003",
                "199804132025211030",
                "199507092025212045",
                "198308142025211053",
                "199806242025211026",
                "199709112025212037"
            ];
            $komites = [
                "197306041999031006",
                "196307121989032004",
                "196608071993021001",
                "197301021999032001",
                "195708011983031007",
                "197603232005011008",
                "197511102006042002",
                "198011232008012013",
                "197103042001121002",
                "197205252001121004",
                "197206291997032004",
                "197504082003122001"
            ];

            $role = 'dosen';

            $statistics = [
                'created' => [
                    'dosen' => 0,
                    'senat' => 0,
                    'kepegawaian' => 0,
                    'comite' => 0,
                ],
                'updated' => [
                    'dosen' => 0,
                    'senat' => 0,
                    'kepegawaian' => 0,
                    'comite' => 0,
                ],
            ];

            foreach ($doens as $dosen) {
                
                $role = 'dosen';

                if (in_array($dosen['nip'], $senats)) {
                    $role = 'senat';
                } elseif (in_array($dosen['nip'], $kepegawaians)) {
                    $role = 'kepegawaian';
                } elseif (in_array($dosen['nip'], $komites)) {
                    $role = 'comite';
                }

                $user = User::firstOrNew([
                    'nip' => $dosen['nip'] == '' ? strtolower(str_replace(' ', '', $dosen['nm'])) . '@gmail.com' : $dosen['nip'],
                ]);

                $isNew = !$user->exists;

                $user->name = $dosen['nm'];
                $user->email = $dosen['nip'] == '' ? strtolower(str_replace(' ', '', $dosen['nm'])) . '@gmail.com' : $dosen['nip'];
                $user->role = $role;

                if ($isNew) {
                    $user->password = Hash::make('silaksan2026');
                }

                $user->save();

                if ($isNew) {
                    $statistics['created'][$role]++;
                } else {
                    $statistics['updated'][$role]++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Sinkronisasi dosen berhasil',
                'summary' => [
                    'created_total' => array_sum($statistics['created']),
                    'updated_total' => array_sum($statistics['updated']),
                ],
                'details' => $statistics,
            ]);

        } catch (Exception $e) {
            Log::error('Error get list data API: ' . $e->getMessage());

            throw $e;
        }
    }
    
}
