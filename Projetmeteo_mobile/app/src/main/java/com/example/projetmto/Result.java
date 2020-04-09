package com.example.projetmto;


import android.content.Intent;
import android.os.Bundle;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import java.io.IOException;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.OkHttpClient;
import okhttp3.Request;
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

        OkHttpClient client = new OkHttpClient();
        String url = "https://77.131.214.150/";

        Request request = new Request.Builder()
                .url(url)
                .build();

        client.newCall(request).enqueue(new Callback() {
            @Override
            public void onFailure(Call call, IOException e) {
                e.printStackTrace();
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
