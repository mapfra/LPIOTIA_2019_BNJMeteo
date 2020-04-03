package com.example.projetmto;

import android.os.Bundle;

import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.snackbar.Snackbar;
import com.google.android.material.tabs.TabLayout;

import androidx.viewpager.widget.ViewPager;
import androidx.appcompat.app.AppCompatActivity;

import android.provider.MediaStore;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

import com.example.projetmto.ui.main.SectionsPagerAdapter;

public class MainActivity extends AppCompatActivity {

    private RadioGroup radioGroup;
    private RadioButton radioTemp;
    private RadioButton radioHum;
    private RadioButton radioLum;
    private RadioButton radioPress;
    private RadioButton radioPreci;
    private RadioButton radioVent;

    private Button buttonValidate;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        /*SectionsPagerAdapter sectionsPagerAdapter = new SectionsPagerAdapter(this, getSupportFragmentManager());
        ViewPager viewPager = findViewById(R.id.view_pager);
        viewPager.setAdapter(sectionsPagerAdapter);*/

        // Initialisation des boutons radio permettant de choisir notre type de relevé

        this.radioGroup = (RadioGroup) this.findViewById(R.id.radioGroup);
        this.radioTemp = (RadioButton) this.findViewById(R.id.radiotemp);
        this.radioHum = (RadioButton) this.findViewById(R.id.radiohum);
        this.radioLum = (RadioButton) this.findViewById(R.id.radiolum);
        this.radioPress = (RadioButton) this.findViewById(R.id.radiopress);
        this.radioPreci = (RadioButton) this.findViewById(R.id.radiopreci);
        this.radioVent = (RadioButton) this.findViewById(R.id.radiovent);

        this.buttonValidate = (Button) this.findViewById(R.id.button);

        this.buttonValidate.setOnClickListener(new Button.OnClickListener() {
            public void onClick(View v) {
                doSave();
            }
        });
    }

    private void doSave() {
        int releved = this.radioGroup.getCheckedRadioButtonId();
        RadioButton radioButtonReleved = (RadioButton) this.findViewById(releved);
        String message = "Le relevé que vous avez choisi est " + radioButtonReleved.getText();
        Toast.makeText(this, message, Toast.LENGTH_LONG).show();
    }

}