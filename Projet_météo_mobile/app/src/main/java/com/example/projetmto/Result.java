package com.example.projetmto;


import android.content.Intent;
import android.os.Bundle;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.MediaType;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;

public class Result extends AppCompatActivity {

    protected TextView mTextView;
    private String datedebut;
    private String datefin;
    private String releve;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.fragment_main);

        mTextView = findViewById(R.id.section_label);

        Intent i = getIntent();
        datedebut = i.getStringExtra("datedebut");
        datefin = i.getStringExtra("datefin");
        releve = i.getStringExtra("releve");

        if(datedebut == null) {
            mTextView.setText("Datedebut vide");
        }

        if(datefin == null) {
            mTextView.setText("Datefin vide");
        }

        if(releve == null) {
            mTextView.setText("Releve vide");
        }

        MediaType MEDIA_TYPE = MediaType.parse("application/json");
        String url = "https://77.131.214.150/";

        OkHttpClient client = new OkHttpClient();

        JSONObject postdata = new JSONObject();
        try {
            postdata.put("date_debut", datedebut);
            postdata.put("date2", datefin);
            postdata.put("selection", releve);
        } catch(JSONException e){
            // TODO Auto-generated catch block
            e.printStackTrace();
        }

        RequestBody body = RequestBody.create(MEDIA_TYPE, postdata.toString());

        Request request = new Request.Builder()
                .url(url)
                .post(body)
                .header("Accept", "application/json")
                .header("Content-Type", "application/json")
                .build();

        client.newCall(request).enqueue(new Callback() {
            @Override
            public void onFailure(Call call, IOException e) {
                final String falseResponse = e.getMessage().toString();
                Result.this.runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        mTextView.setText(falseResponse);
                    }
                });
            }

            @Override
            public void onResponse(Call call, Response response) throws IOException {
                if(response.isSuccessful()) {
                    final String myResponse = response.body().string();

                    Result.this.runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            mTextView.setText(myResponse);
                        }
                    });
                }
            }
        });

    }
}
