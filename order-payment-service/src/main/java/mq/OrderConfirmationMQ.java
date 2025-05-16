package mq;

import com.rabbitmq.client.Channel;
import com.rabbitmq.client.Connection;
import com.rabbitmq.client.ConnectionFactory;
import com.rabbitmq.client.DeliverCallback;

import services.OrderService;

import javax.annotation.PostConstruct;
import javax.ejb.Singleton;
import javax.ejb.Startup;
import javax.inject.Inject;

import java.io.IOException;
import java.util.concurrent.TimeoutException;

@Singleton
@Startup
public class OrderConfirmationMQ {
	@Inject
	private OrderService service;
    private Channel channel;
    private Connection connection;
    private final String QUEUE_NAME = "order_check_queue";

    @PostConstruct
    public void init() {
        try {
            ConnectionFactory factory = new ConnectionFactory();
            factory.setHost("localhost");
            factory.setUsername("guest");
            factory.setPassword("guest");
            connection = factory.newConnection();
            channel = connection.createChannel();
            channel.queueDeclare(QUEUE_NAME, true, false, false, null);
            
            DeliverCallback deliverCallback = (consumerTag, delivery) -> {
                String message = new String(delivery.getBody(), "UTF-8");
                handleInventoryConfirmation(message);
            };
            channel.basicConsume(QUEUE_NAME, true, deliverCallback, consumerTag -> {});
        } catch (IOException | TimeoutException e) {
            throw new RuntimeException("Failed to initialize RabbitMQ", e);
        }
    }

    public void handleInventoryConfirmation(String payloadJson) {
    	System.out.println(">>>>> Deliverd an order confirmation: " + payloadJson);
        service.orderConfirmation(payloadJson);
    }
}
