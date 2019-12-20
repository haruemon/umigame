<?php

namespace App\Http\Controllers;

use App\Word;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class UmigameController extends Controller
{

    protected $accessToken;
    protected $client;

    public function __construct()
    {
        $uri = "https://api.ce-cotoha.com/v1/oauth/accesstokens";

        $this->client = new Client(['headers' => [
            'Content-Type' => 'application/json;charset=UTF-8'
        ]]);
        $response = $this->client->post($uri, [
            'json' => [
                'grantType' => 'client_credentials',
                'clientId' => 'WrjcGnVAZEtJ5s0yjvDAAbPAynncTIsu',
                'clientSecret' => 'a0hrUjPmkutuzbfg',
            ]
        ]);
        $responseJsonData = json_decode($response->getBody()->getcontents());
        $this->accessToken = $responseJsonData->access_token;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $resultText = 'ここに質問の回答が表示されます。';
        return view('umigame', compact('resultText'));
    }
    public function result()
    {
        return view('result');
    }

    public function answer(Request $request)
    {
        //$texts = ['男の死はスープが原因ですか？', '男の過去が関係しますか？', '男はほんとは死んでいませんか？', 'スープが原因で男は死にましたか？'];
        $checkWord = [];
        $questions = $this->parsing($request->text);
        $check = ['名詞', '判定詞', '動詞'];
        $text = '';
        foreach($questions['result'] as $result) {
            foreach($result->tokens as $token) {
                if (in_array($token->pos, $check)) {
                    $checkWord[] = $token->form;
                }
                if ($token->pos === '動詞語幹') {
                    $text .= $token->form;
                }
                if ($token->pos === '動詞活用語尾') {
                    $text .= $token->form;
                }
                if ($token->pos === '動詞接尾辞') {
                    $text .= $token->form;
                    $checkWord[] = $text;
                    $text = '';
                }
            }
        }

        //データ整形
        $checkData = [];
        foreach ($checkWord as $check) {
            $word = Word::whereText($check)->first();
            if (is_null($word)) continue;
            $checkData[$word->level][$word->id] = $word;
        }
        ksort($checkData);

        //データ判定
        $isLast = null;
        foreach($checkData as $level => $levelWords) {
            if ($level == 0) continue;
            foreach($levelWords as $word) {
                foreach($word->parent_ids as $level => $id) {
                    if (isset($checkData[$level]) && isset($checkData[$level][$id])) {
                        $isLast = $word;
                        break;
                    }
                }
            }
        }

        $resultText = '関係ありません。';
        if (!is_null($isLast)) {
            $resultText = $isLast->result_text;
        }

        return view('umigame', compact('resultText'));
    }

    public function parsing($text)
    {
        $uri = 'https://api.ce-cotoha.com/api/dev/nlp/v1/parse';
        $response = $this->client->post($uri, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken
            ],
            'json' => [
                'sentence' => $text,
                'type' => 'default'
            ]
        ]);
        $responseJsonData = collect(json_decode($response->getBody()->getcontents()));
        return $responseJsonData;
    }

}
