package mq;

import com.fasterxml.jackson.core.JsonProcessingException;
import com.rabbitmq.client.*;

import services.InventoryService;

import javax.annotation.PostConstruct;
import javax.annotation.PreDestroy;
import javax.ejb.Singleton;
import javax.ejb.Startup;
import javax.inject.Inject;

@Singleton
@Startup
public class InventoryCheckMQ {
	@Inject
	InventoryService service;
	
    private static final String QUEUE_NAME = "inventory_check_queue";

    @PostConstruct
    public void init() {
        try {
            ConnectionFactory factory = new ConnectionFactory();
            factory.setHost("localhost");
            factory.setUsername("guest");
            factory.setPassword("guest");
            Connection connection = factory.newConnection();
            Channel channel = connection.createChannel();
            channel.queueDeclare(QUEUE_NAME, true, false, false, null);

            DeliverCallback deliverCallback = (consumerTag, delivery) -> {
                String message = new String(delivery.getBody(), "UTF-8");
                handleInventoryCheck(message);
            };
            channel.basicConsume(QUEUE_NAME, true, deliverCallback, consumerTag -> {});
        } catch (Exception e) {
            throw new RuntimeException("Failed to initialize MQConsumer", e);
        }
    }

    private void handleInventoryCheck(String message) throws JsonProcessingException {
    	System.out.println("=====> Delivered a check request " + message);
        service.handleInventoryCheck(message);
    }
}

