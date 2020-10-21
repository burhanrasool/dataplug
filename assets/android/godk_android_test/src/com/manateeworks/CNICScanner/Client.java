package com.manateeworks.CNICScanner;

import java.io.BufferedWriter;
import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.net.InetAddress;
import java.net.Socket;
import java.net.UnknownHostException;

public class Client implements Runnable {
	
	private Socket socket;
	private int SERVERPORT = 7482;
	private String SERVER_IP = "10.50.49.207";
	private String send;

	public Client()
	{
		
	}
	public Client(String server, int port)
	{
		SERVER_IP = server;
		SERVERPORT = port;
		try {
			InetAddress serverAddr = InetAddress.getByName(SERVER_IP);
			socket = new Socket(serverAddr, SERVERPORT);

		} catch (UnknownHostException e1) {
			e1.printStackTrace();
		} catch (IOException e1) {
			e1.printStackTrace();
		} catch(Exception e){
			e.printStackTrace();
		}
	}
	
	@Override
	public void run() {
		
		try {
			@SuppressWarnings("unused")
			DataInputStream in = new DataInputStream(socket.getInputStream());
			PrintWriter out = new PrintWriter(new BufferedWriter(new OutputStreamWriter(socket.getOutputStream())),true);
		//	out.print(send.length());
		//	int ack = in.read();
		//	if(ack==1)
			out.println(send);
			
		} catch (UnknownHostException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		} catch (Exception e) {
			e.printStackTrace();
		}
		
		try {
			socket.close();
		} catch (IOException e) {
			e.printStackTrace();
		}

	}
	
	public void sendData(String sen) {
		
		try {
			//DataInputStream in = new DataInputStream(socket.getInputStream());
			
			OutputStream out1 = socket.getOutputStream(); 
		    @SuppressWarnings("unused")
			DataOutputStream dos = new DataOutputStream(out1);
			PrintWriter out = new PrintWriter(new BufferedWriter(new OutputStreamWriter(socket.getOutputStream())),true);
		//	out.print(send.length());
		//	int ack = in.read();
		//	if(ack==1)
			
			out.println(sen);
			
		} catch (UnknownHostException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		} catch (Exception e) {
			e.printStackTrace();
		}
		
	}
public void sendData(byte[] sen) {
		
		try {
			//DataInputStream in = new DataInputStream(socket.getInputStream());
			
			OutputStream out1 = socket.getOutputStream(); 
		    DataOutputStream dos = new DataOutputStream(out1);
			//PrintWriter out = new PrintWriter(new BufferedWriter(new OutputStreamWriter(socket.getOutputStream())),true);
		//	out.print(send.length());
		//	int ack = in.read();
		//	if(ack==1)
			dos.write(sen);
//			out.println(sen);
			
		} catch (UnknownHostException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		} catch (Exception e) {
			e.printStackTrace();
		}
		
	}
	public void CloseClient()
	{
		try {
			socket.close();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}

}
