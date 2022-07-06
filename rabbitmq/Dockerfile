FROM rabbitmq:3.10.5-management-alpine

RUN rabbitmq-plugins enable --offline rabbitmq_mqtt rabbitmq_federation_management rabbitmq_stomp
RUN rabbitmq-plugins enable --offline rabbitmq_management

EXPOSE 5672 15672