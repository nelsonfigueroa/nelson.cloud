+++
title = "Learning Kubernetes with Minikube"
description = "Learn Kubernetes with Minikube"
date = "2020-01-04"
categories = ["devops"]
tags = ["kubernetes", "minikube", "orchestration", "containers", "docker"]
+++

{{< figure src="/learning_kubernetes_with_minikube/kubernetes.png" >}}

## Introduction

Kubernetes, also known as "k8s", is an open-source container orchestration tool designed to automate deploying, scaling, and operating application containers. Docker containers can be used to develop and build applications, then Kubernetes can be used to run these applications. While you can use other container engines with Kubernetes, I'll be using Docker since it is the most popular. With that being said, previous Docker experience is recommended for this post to make more sense, at the very least an understanding of container concepts.

## Clusters, Nodes, and Pods

The following is Kubernetes architecture in a nutshell:

At the highest level, we have **Clusters**. Each Cluster contains a **Master Node** and several **Worker Nodes**. Worker Nodes each have **Pods**, which are just a group of containers. So the order from top to bottom is:

Cluster -> Master Node/Worker Nodes -> Pods -> Containers

Now, let's dive deeper into each of these objects.

### Clusters

Each cluster contains a single master node and multiple worker nodes. Each node containts its own processes. The master node is responsible for managing the cluster. In a production environment, it is recommended to have at least a three-node cluster in addition to the master node.

### Master Node

The master node is responsible for the overall management of the cluster. It has an API Server, a Scheduler, a Controller Manager, and a distributed key-value store called `etcd`.

- The API Server allows you to interact with the Kubernetes API.
- The scheduler watches created pods who do not ave a node assigned yet, and assigns the pod to run on a specific node.
- The Controller Manager runs controllers, which are simply background threads that run tasks in the cluster.
- The `etcd` store is used as a database for cluster datta such as job scheduling information and pod details.

We interact with the master node using the `kubectl` command in the terminal. `kubectl` has a configuration file called `kubeconfig` that has server information and authentication information to access the API server.

### Worker Nodes

These nodes are where applications operate. Worker nodes can be physical or virtual machines. They communicate back with the master node. Worker nodes can be exposed to the internet through a load balancer. There are three things running in each worker node: a process called `kubelet`, a process called `kube-proxy`, and a container engine.

- The `kubelet` process is responsible for pod management within the node. It's an agent that communicates with the API server to see if pods have been assigned to the nodes, executes pod containers via the container engine, mounts and runs pod volumes and secrets, and responds back to the master node.

- The `kube-proxy` process is a network proxy and load balancer for the service on a single worker node. It handles network routing for TCP and UDP packets and performs connection forwarding. Any traffic coming into the node is handled by this process. This is how an end-user ends up talking to a Kubernetes application.

- The Container Engine works together with the `kubelet` process to run containers on the node. Although in this post I'm using Docker, you can use other container engines. Containers of an application are tightly coupled together in a Pod.

### Pods

A Pod is a single instance of a running process in your cluster. It is the smallest unit you can interact with in Kubernetes. A Pod is made up of a group of containers inside a Node that share storage, Linux namespace, and IP addresses.

When pods are deployed and running, the `kubelet` process in the node communicates with the pods to check on state and health. The `kube-proxy` process in the node routes any packets to the pods from other resources.

Pods are designed to be disposable. They never self-heal and are not restarted by the scheduler itself. You should never create pods just by themselves, always user higher-level constructs to manage pods.

Pods have several states: pending, running, succeeded, failed, and CrashLoopBackOff

- `pending`: A pod as been accepted by Kubernetes but a container has not been created yet.
- `running`: A pod has been scheduled on a node and all of its containers have been created, and at least one container is in a running state
- `succeeded`: All the containers in a pod have exited with a status of 0 which means success. These containers will not be restarted.
- `failed`: All containers in the pod have exited and at least one container has failed and returned a non-zero exit status.
- `CrashLoopBackOff`: A container has failed to start and Kubernetes is repeatedly trying to restart the pod.

## Deployments

A Deployment is a representation of multiple identical pods, and how we describe a desired state in Kubernetes. After describing a desired state, Kubernetes then changes the actual state to match the state we want. We can create a Deployment directly in the command line like this:

```
$ kubectl create deployment mydeployment --image=nginx:1.7.9
```

We can also create a Deployment through a YAML file and specify more information, such as the number of pods we want using the `replicas` line:

```yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: nginx-deployment
  labels:
    app: nginx
spec:
  replicas: 3
  selector:
    matchLabels:
      app: nginx
  template:
    metadata:
      labels:
        app: nginx
    spec:
      containers:
      - name: nginx
        image: nginx:1.7.9
        ports:
        - containerPort: 80

```

And then apply the YAML with the following command:

```
$ kubectl apply -f mydeployment.yaml
```

The Deployment will ensure that the number of Pods we want are running and available at all times. 

## Services

A Service is an abstract way to expose an application running on a set of pods as a network service. Kubernetes automatically assigns pods an IP address, a single DNS name for a set of pods, and can load-balance traffic across pods.

Deployments are used to describe a state and update Pods and applications, then Services are used to expose these Pods to make them accessible by users. You can also use Services to expose two deployments and get them to talk to each other, such as frontend and backend pods.

There are four types of Services in Kubernetes

- `ClusterIP`: The default type of service. It exposes the service internally in the cluster and can only be reached from within the cluster.
- `NodePort`: This type of service exposes the service on each node's IP on a static port. A ClusterIP service is automatically created and the NodePort service will route to it. You can access the NodePort service from outside of the cluster by using the NodeIP:NodePort socket.
- `LoadBalancer`: This service type exposes the service externally using the load balancer of your chosen cloud provider. The external load balancer routes to NodePort and ClusterIP services which are automatically created.
- `ExternalName`: Maps the service to to the contents of the `ExternalName` field. No Proxying of any kind is set up.

If a Deployment named `mydeployment` has been created, we can create a Service using the command line as such:

```
$ kubectl create service nodeport mydeployment --tcp=80
```

Just like with Deployments, we can also specify a Service using a YAML file:

```yaml
apiVersion: v1
kind: Service
metadata:
  name: myservice
spec:
  selector:
    app: rubyapp
    department: devs
  type: NodePort
  ports:
  - protocol: TCP
    port: 80
    targetPort: 8080
```

And apply it using

```
$ kubectl apply -f myservice.yaml
```

Now that the Kubernetes concepts and terminology have been covered, we can start getting hands-on experience using Minikube.

## Minikube

Minikube is a tool that will start up a single-node Kubernetes cluster on a virtual machine on our computer. It's great for getting comfortable with Kubernetes commands. 

### Installing

I'll be showing steps to install Minikube on MacOS using `brew`. If you're running Windows, Your best bet is to refer to the [official Kubernetes documentation](https://kubernetes.io/docs/tasks/tools/install-minikube/).

You'll need a hypervisor to run Minikube on. I chose VirtualBox, and installing it was easy as running:

```
$ brew cask install virtualbox
```

Then we can install Minikube by running

```
$ brew install minikube
```

After installation is complete, start up minikube

```
$ minikube start
```

This command will download the Minkube .iso and run it using Virtualbox.

We can double check that everything is working by running the following:

```
$ minikube status

host: Running
kubelet: Running
apiserver: Running
kubeconfig: Configured
```

We can also run Kubernetes commands to verify that we have a node running:

```
$ kubectl get nodes

NAME       STATUS   ROLES    AGE   VERSION
minikube   Ready    master   42s   v1.17.0
```

### 'Hello World' in Kubernetes

Now that Minkube is set up, we'll run our first application. We're going to pull a simple container that displays "Hello World" on a browser.

First, create a deployment:

```
$ kubectl create deployment helloworld --image=karthequian/helloworld
```

Then, create a service:

```
$ kubectl create service nodeport helloworld --tcp=80
```

Then, we can access the service through Minikube with the following command:

```
$ minikube service helloworld
```


You should get similar output to this:

```
|-----------|------------|-------------|-----------------------------|
| NAMESPACE |    NAME    | TARGET PORT |             URL             |
|-----------|------------|-------------|-----------------------------|
| default   | helloworld |          80 | http://192.168.99.104:31003 |
|-----------|------------|-------------|-----------------------------|
🎉  Opening service default/helloworld in default browser...
```

And your browser should automatically open the IP/port in a new window. You should see a simple Bootstrap page that says "Hello". We have successfully run a container with Kubernetes and accessed the application!

We can view the deployments and services we created with the following commands:

```
$ kubectl get deployments

NAME         READY   UP-TO-DATE   AVAILABLE   AGE
helloworld   1/1     1            1           6m39s
```

```
$ kubectl get services

NAME         TYPE        CLUSTER-IP    EXTERNAL-IP   PORT(S)        AGE
helloworld   NodePort    10.96.77.40   <none>        80:31003/TCP   6m17s
kubernetes   ClusterIP   10.96.0.1     <none>        443/TCP        12m
```

And last, we can view a dashboard of everything running on Minikube through the dashboard:

```
$ minikube dashboard
```

After playing around with the dashboard and application we can shut down Minikube

```
$ minikube stop
```

Or if you don't plan on using it for a while, feel free to delete the virtual machine:

```
$ minikube delete
```

## Conclusion

In this post we went through the Kubernetes architecture and covered a lot of terminology. Then, we tried out running Kubernetes locally using Minikube. There's a lot more functinonality that I did not cover, but this is enough to get started with Kubernetes. I will most likely write up a deep-dive of Kubernetes components and how it works under the hood. 