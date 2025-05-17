package mq;

import com.rabbitmq.client.Channel;
import com.rabbitmq.client.Connection;
import com.rabbitmq.client.ConnectionFactory;

import javax.annotation.PostConstruct;
import javax.ejb.Singleton;
import javax.ejb.Startup;

import java.io.IOException;
import java.util.concurrent.TimeoutException;

@Singleton
@Startup
public class InventoryCheckMQ {

    private Channel channel;
    private Connection connection;
    private final String QUEUE_NAME = "inventory_check_queue";

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
        } catch (IOException | TimeoutException e) {
            throw new RuntimeException("Failed to initialize RabbitMQ", e);
        }
    }

    public void sendInventoryCheck(String payloadJson) {
        try {
            channel.basicPublish("", QUEUE_NAME, null, payloadJson.getBytes());
        } catch (IOException e) {
            throw new RuntimeException("Failed to send message to queue", e);
        }
    }

    public void close() {
        try {
            channel.close();
            connection.close();
        } catch (Exception e) {
            // Log
        }
    }
}
