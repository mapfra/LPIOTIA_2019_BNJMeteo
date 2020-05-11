package com.example.projetmto;

import android.content.Intent;
import android.os.Bundle;
import androidx.appcompat.app.AppCompatActivity;

import android.os.Parcelable;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

import java.io.IOException;

import okhttp3.Call;
import okhttp3.Callback;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.Response;

public class MainActivity extends AppCompatActivity {

    private EditText datedebut;
    private EditText datefin;

    private RadioGroup radioGroup;
    /*private RadioButton radioTemp;
    private RadioButton radioHum;
    private RadioButton radioLum;
    private RadioButton radioPress;
    private RadioButton radioPreci;
    private RadioButton radioVent;*/

    private Button buttonValidate;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        /*SectionsPagerAdapter sectionsPagerAdapter = new SectionsPagerAdapter(this, getSupportFragmentManager());
        ViewPager viewPager = findViewById(R.id.view_pager);
        viewPager.setAdapter(sectionsPagerAdapter);*/

        this.datedebut = (EditText) this.findViewById(R.id.dateDebut);
        this.datefin = (EditText) this.findViewById(R.id.dateFin);

        // Initialisation des boutons radio permettant de choisir notre type de relevé

        this.radioGroup = (RadioGroup) this.findViewById(R.id.radioGroup);
        /*this.radioTemp = (RadioButton) this.findViewById(R.id.radiotemp);
        this.radioHum = (RadioButton) this.findViewById(R.id.radiohum);
        this.radioLum = (RadioButton) this.findViewById(R.id.radiolum);
        this.radioPress = (RadioButton) this.findViewById(R.id.radiopress);
        this.radioPreci = (RadioButton) this.findViewById(R.id.radiopreci);
        this.radioVent = (RadioButton) this.findViewById(R.id.radiovent);*/

        this.buttonValidate = (Button) this.findViewById(R.id.button);

        this.buttonValidate.setOnClickListener(new Button.OnClickListener() {
            public void onClick(View v) {
                doSave();
            }
        });
    }

    private void doSave() {
        int releved = this.radioGroup.getCheckedRadioButtonId();
        final RadioButton radioButtonReleved = (RadioButton) this.findViewById(releved);
        String message = "Le relevé que vous avez choisi est " + radioButtonReleved.getText();
        message += "\nLa date est contenu entre " + this.datedebut + " et " + this.datefin;
        Toast.makeText(this, message, Toast.LENGTH_LONG).show();

        Intent myIntent = new Intent(MainActivity.this, Result.class);
        myIntent.putExtra("datedebut", datedebut.getText());
        myIntent.putExtra("datefin", datefin.getText());
        myIntent.putExtra("releve", radioButtonReleved.getText());
        startActivity(myIntent);
    }

}