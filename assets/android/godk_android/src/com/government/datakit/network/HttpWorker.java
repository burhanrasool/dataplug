package com.government.datakit.network;


import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.UnsupportedEncodingException;
import java.util.List;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.HttpVersion;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.CoreProtocolPNames;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.params.HttpParams;

/**
 * 
 * @author gulfamhassan
 *
 */

public class HttpWorker {
	
	
	private InputStream inputStream = null;
    private StringBuilder stringBuilder;
    // constructor
    public HttpWorker() {
 
    }
 
    public String getData(String url, List<NameValuePair> params) {
 
        try {
        	
//            DefaultHttpClient httpClient = new DefaultHttpClient();

        	//////////////////////////////////////////////////////////////
        	
            DefaultHttpClient httpClient = new DefaultHttpClient();
            HttpParams httpParameters = httpClient.getParams();
            httpParameters.setParameter(CoreProtocolPNames.PROTOCOL_VERSION, HttpVersion.HTTP_1_1);
            HttpConnectionParams.setConnectionTimeout(httpParameters, 111111111);
            HttpConnectionParams.setSoTimeout(httpParameters, 111111111);
            HttpConnectionParams.setTcpNoDelay(httpParameters, true);
            
            
            /////////////////////////////////////////////////////////////
            
            HttpPost httpPost = new HttpPost(url);           
            httpPost.setEntity(new UrlEncodedFormEntity(params, "UTF-8"));
 
            HttpResponse httpResponse = httpClient.execute(httpPost);
            HttpEntity httpEntity = httpResponse.getEntity();
            inputStream = httpEntity.getContent();
        	BufferedReader reader = new BufferedReader(new InputStreamReader(inputStream));
        	stringBuilder = new StringBuilder();
            String line = null;
            while ((line = reader.readLine()) != null) {
                stringBuilder.append(line);
//                stringBuilder.append(line + "n");
            }
            inputStream.close();
        } catch (UnsupportedEncodingException uee) {
        	uee.printStackTrace();
            return "Network Error";
        } catch (ClientProtocolException cpe) {
        	cpe.printStackTrace();
        	return "Network Error";
        } catch (IOException ioe) {
        	ioe.printStackTrace();
        	return "Network Error";
        }catch (Exception e) {
        	e.printStackTrace();
        	return "Network Error";
        }
        return stringBuilder.toString();
    }

}
