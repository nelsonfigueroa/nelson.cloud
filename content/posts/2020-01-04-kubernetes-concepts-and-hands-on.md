+++
title = "Kubernetes Concepts and Hands-On with Minikube"
summary = "Learn Kubernetes concepts and get hands-on experience with Minikube"
date = "2020-01-04"
lastmod = "2020-01-04"
categories = ["Kubernetes"]
keywords = ["Kubernetes", "k8s", "Minikube", "container orchestration", "Docker containers", "pods", "deployments", "services", "kubectl", "Kubernetes tutorial"]
ShowToc = true
TocOpen = true
+++

## Introduction

Kubernetes, also known as "k8s", is an open-source container orchestration tool designed to automate deploying, scaling, and operating application containers. Docker containers can be used to develop and build applications, then Kubernetes can be used to run these applications. You can use other container engines with Kubernetes, but I'll be using Docker since it is the most popular.

Previous Docker experience is recommended for this post to make more sense, at the very least an understanding of container concepts.

## Kubernetes Objects

The following is Kubernetes in a nutshell:

At the highest level, we have **Clusters**. Each Cluster contains a **Master Node** and several **Worker Nodes**. Worker Nodes each have **Pods**, which are just a group of containers. So the order from top to bottom is:

Cluster -> Master Node/Worker Nodes -> Pods -> Containers

Each of these objects are declared through **Deployments** and **Services** within a specified **Context** and **Namespace**. Deployments, Services, Contexts, and Namespaces can be declared through the command line or using YAML files.

The `kubectl` command is how we interact with Kubernetes objects.

Now, let's dive deeper into each of these objects.

### Clusters

Each cluster contains a single master node and multiple worker nodes. Each node contains its own processes. The master node is responsible for managing the cluster. In a production environment, it is recommended to have at least a three-node cluster in addition to the master node.

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

### Contexts

A Context simply refers to a Kubernetes Cluster. Each Cluster that is created will have its own Context. Contexts tell the `kubectl` command to which Cluster to run commands against. We'll get some hands-on experience with Contexts in a later section.

### Namespaces

Namespaces are virtual clusters within a single physical cluster. Within a single Cluster, you can define several namespaces to logically divide resources and applications. Similar to Contexts, we use Namespaces to further specify to `kubectl` what objects we want to interact with.

### Deployments

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

### Services

A Service is an abstract way to expose an application running on a set of pods as a network service. Kubernetes automatically assigns pods an IP address, a single DNS name for a set of pods, and can load-balance traffic across pods.

Deployments are used to describe a state and update Pods and applications, then Services are used to expose these Pods to make them accessible by users. You can also use Services to expose two deployments and get them to talk to each other, such as frontend and backend pods.

There are four types of Services in Kubernetes

- `ClusterIP`: The default type of service. It exposes the service internally in the cluster and can only be reached from within the cluster.
- `NodePort`: This type of service exposes the service on each node's IP on a static port. A ClusterIP service is automatically created and the NodePort service will route to it. You can access the NodePort service from outside of the cluster by using the NodeIP:NodePort socket.
- `LoadBalancer`: This service type exposes the service externally using the load balancer of your chosen cloud provider. The external load balancer routes to NodePort and ClusterIP services which are automatically created.
- `ExternalName`: Maps the service to to the contents of the `ExternalName` field. No Proxying of any kind is set up.

If a Deployment named `mydeployment` has been previously created, we can create a Service using the command line as such:

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

## Kubernetes Hands-On with Minikube

Minikube is a tool that will start up a single-node Kubernetes cluster on a virtual machine on our computer. It's great for getting comfortable with Kubernetes commands.

### Installing Minikube

I'll be showing steps to install Minikube on macOS using `brew`. If you're running Windows, Your best bet is to refer to the [official Kubernetes documentation](https://kubernetes.io/docs/tasks/tools/install-minikube/).

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

We can also run Kubernetes commands to verify that we have a Node running:

```
$ kubectl get nodes

NAME       STATUS   ROLES    AGE   VERSION
minikube   Ready    master   42s   v1.17.0
```

And we have confirmation that a single Node is up and running!

### Deploying An Image Through The Command Line

Now that Minkube is set up and we have a Cluster running, let's recap some of the concepts we covered above. Recall Contexts and Namespaces.

By default, Minikube created a Cluster with a Context called 'minikube'. We can see this by running:

```
$ kubectl config get-contexts

CURRENT   NAME       CLUSTER    AUTHINFO   NAMESPACE
*         minikube   minikube   minikube

```

Minikube also created a default Namespace simply called 'default'. View it by running:

```
$ kubectl get namespaces

NAME                   STATUS   AGE
default                Active   1m
kube-node-lease        Active   1m
kube-public            Active   1m
kube-system            Active   1m
kubernetes-dashboard   Active   1m
```

There are additonal Namespaces here used by core Kubernetes services, but we don't have to worry about those.

All of our `kubectl` commands right now will run against the 'default' Namespace within the 'minikube' Context. If in the future you have a Cluster running, say, on Amazon Web Services, you can switch to that Context/Namespace and run commands against that Cluster.

Now, let's deploy a simple container that displays "Hello World" on a browser.

First, create a deployment specifying the image to use:

```
$ kubectl create deployment helloworld --image=karthequian/helloworld
```

Then, create a service to expose the deployment:

```
$ kubectl create service nodeport helloworld --tcp=80
```

Now we can access the service through Minikube with the following command:

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

Now let's recap some more concepts. Recall Deployments, Services, Nodes, and Pods.

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

There's a default 'kubernetes' service that we can ignore. The one we created is 'helloworld'.The Deployment we created specified the state we want our Cluster to be in, while the Service we created exposed the Pod created by the Deployment.

We can also view the single Node in our Cluster:

```
$ kubectl get nodes

NAME       STATUS   ROLES    AGE   VERSION
minikube   Ready    master   10m   v1.17.0
```

And we can view the Pod that was created by the Deployment:

```
$ kubectl get pods

NAME                          READY   STATUS    RESTARTS   AGE
helloworld-7f9bdc6489-tdpd6   1/1     Running   0          2m9s
```

And finally, we can view everything running on Minikube through the dashboard:

```
$ minikube dashboard
```

You'll notice that there are a lot more Kubernetes Objects and features in the dashboard that I have not covered. Those are beyond the scope of this post and for more advanced purposes. However, feel free to read up on those once you have a solid grasp of the concepts covered in this post.

While it's nice looking at a dashboard, I highly recommend getting comfortable with the `kubectl` command to view and manage Kubernetes resources.

### Deploying Using YAML Manifests

In an earlier section, I briefly mentioned that you can specify Kubernetes objects using YAML files. We're going to deploy a Ruby on Rails application on Minikube using YAML files. (*Note: The application was created by me for learning purposes, but we could be using any other image for this*)

Using YAML files are useful because they allow you to version control your Kubernetes resources and modify them in a single file. Whenever changes are made to a YAML file, we simply need to run `kubectl apply` as you'll see soon. This process can even be automated in a CI service.

First, let's start a fresh Minikube instance. Go ahead and delete the current Minikube VM and start another one:

```
$ minikube delete

$ minikube start
```

Next, copy these two YAML files locally in your system:

**deployment.yaml**

```yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: forum-deployment
spec:
  replicas: 3
  selector:
    matchLabels:
      app: forum
  template:
    metadata:
      labels:
        app: forum
    spec:
      containers:
      - name: forum
        image: nfigueroa/forum
        ports:
        - containerPort: 3000
```

**service.yaml**

```yaml
apiVersion: v1
kind: Service
metadata:
  name: forum-service
spec:
  type: NodePort
  selector:
    app: forum
  ports:
    - nodePort: 30000
      port: 3000
```

Take some time to read through the files to get some idea of what's going on. Kubernetes YAML files can be used for deep configuration, too much to cover in a single post. I recommend reading through documentation to better understand what's possible in a YAML file.

For now, understand that the Deployment is specifying an image to pull (`nfigueroa/forum`), a port to expose on the container (`containerPort`), and the number of pods we want (`replicas`).

The Service is specifying that we want to expose a Node port (`nodePort`) on port 30000, and this Node port will point to port 3000 in the pod.

Let's apply the Deployment YAML:

```
$ kubectl apply -f deployment.yaml

deployment.apps/forum-deployment created
```

Once the Deployment has been created, we can view it:

```
$ kubectl get deployments

NAME               READY   UP-TO-DATE   AVAILABLE   AGE
forum-deployment   0/3     3            0           5s
```

And we can see the Pods starting up:

```
$ kubectl get pods

NAME                              READY   STATUS              RESTARTS   AGE
forum-deployment-8d5dd5b9-289m7   0/1     ContainerCreating   0          35s
forum-deployment-8d5dd5b9-7qq52   0/1     ContainerCreating   0          35s
forum-deployment-8d5dd5b9-xllg4   0/1     ContainerCreating   0          35s
```

Next, let's apply the Service YAML:

```
$ kubectl apply -f service.yaml

service/forum-service created
```

We can view the Service:

```
$ kubectl get services

NAME            TYPE        CLUSTER-IP    EXTERNAL-IP   PORT(S)          AGE
forum-service   NodePort    10.96.94.69   <none>        3000:30000/TCP   4s
kubernetes      ClusterIP   10.96.0.1     <none>        443/TCP          3m14s
```

Now we can view our application with the same command we used before:

```
minikube service forum-service
```

And your browser should automatically take you to the landing page. If for any reason the landing page is blank, double check that the pods are running. It might take a while to initialize the application.

YAML files can be created for other Kubernetes Objects as well, like Pods and Namespaces. While it is okay to create a YAML for an individual Namespace, it is not recommended to create individual Pods this way unless it is for testing purposes or a very specific situation. Always use higher level abstractions to create pods, like Deployments.

Feel free to try the YAML files with your own Docker images, just make sure you change the ports to the ones your application needs.

Refer to the Kubernetes documentation and see what other Objects and configurations can be declared using YAML files. There is too much to cover in this post.

### Cleaning Up

After you're done playing with Minikube you can shut it down so it doesn't use up resources:

```
$ minikube stop
```

If you want to completely remove the Minikube virtual machine, run:

```
$ minikube delete
```

## Conclusion

In this post, I covered the essential Kubernetes concepts. Then, we ran Kubernetes locally using Minikube and manually deployed a Docker image. After that, we deployed an application using YAML files.

There's a lot more functionality that I did not cover, but this is enough to get started. Kubernetes is a complex piece of software and has a tough learning curve, but it is very rewarding. From this point forward, read the Kubernetes documentation and get a deeper understanding.
