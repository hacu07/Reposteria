package com.hacu.samyreposteria;

import android.app.ProgressDialog;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.hacu.samyreposteria.Utilidades.VolleySingleton;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

/*
* Actividad encargada del LOGIN de la APP
* Autor: Harold Cupitra
* Fecha: 06/10/2018
* */

public class MainActivity extends AppCompatActivity {
    private static final String TAG = "MainActivity";

    ProgressDialog progressDialog;
    private EditText loginInputEmail, loginInputPassword;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        loginInputEmail = findViewById(R.id.login_input_email);
        loginInputPassword = findViewById(R.id.login_input_password);
        //Progress Dialog
        progressDialog = new ProgressDialog(this);
        progressDialog.setCancelable(false);


    }

    public void iniciarSesion(View view) {
        String cancel_req_tag = "login";
        progressDialog.setMessage(getString(R.string.msj_validando_login));
        showDialog();
        String url_login = getString(R.string.ip)+"login.php";

        StringRequest strReq = new StringRequest(Request.Method.POST,
                url_login, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Log.d(TAG, "Register Response: " + response.toString());
                hideDialog();
                try {
                    JSONObject json = new JSONObject(response);
                    boolean error = json.getBoolean("error");

                    if (!error) {
                        String user = json.getJSONObject("user").getString("correo");
                        //Se lanza la actividad a mostrar desps del inicio de sesion exitoso
                        Intent intent = new Intent(MainActivity.this,PruebaActivity.class);
                        intent.putExtra("username",user);
                        startActivity(intent);
                        finish();
                    }else{
                        imprimirMensaje(json.getString("error_msg"));
                    }
                } catch (Exception e) {
                    e.printStackTrace();
                    imprimirMensaje("Error de codigo en login");
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e(TAG,"Login Error: " + error.getMessage());
                imprimirMensaje(error.getMessage());
                hideDialog();
            }
        }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                //Posting params to login url
                Map<String, String> params = new HashMap<String,String>();
                params.put("email",loginInputEmail.getText().toString());
                params.put("password",loginInputPassword.getText().toString());
                return params;
            }
        };
        //Adding request to request queue
        VolleySingleton.getInstanciaVolley(getApplicationContext()).addToRequestQueue(strReq,cancel_req_tag);
    }


    public void enviarFormularioRegistro(View view) {

    }

    private void imprimirMensaje(String mensaje){
        Toast.makeText(this,mensaje,Toast.LENGTH_SHORT).show();
    }

    private void showDialog() {
        if (!progressDialog.isShowing()){
            progressDialog.show();
        }
    }

    private void hideDialog(){
        if (progressDialog.isShowing()){
            progressDialog.dismiss();
        }
    }
}
