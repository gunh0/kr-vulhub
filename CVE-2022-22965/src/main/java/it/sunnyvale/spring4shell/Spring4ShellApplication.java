package it.sunnyvale.spring4shell;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.boot.web.servlet.support.SpringBootServletInitializer;


@SpringBootApplication
public class Spring4ShellApplication extends SpringBootServletInitializer{
	public static void main(String[] args) {
		SpringApplication.run(Spring4ShellApplication.class, args);
	}

}