+++
title = "Data Structures in Ruby"
description = "An overview of data structures"
date = "2019-09-09"
+++

Due to not being a Computer Science major, my knowledge of data structures and algorithms is weak. I can create a full-stack web application from scratch with no problem. But ask me to perform a binary tree traversal or implement a sorting algorithm and I would be lost. This blog post is my attempt to explain common data structures using Ruby for my own benefit and the 2 other people that might stumble upon this. I'll probably follow up with another separate post focusing on algorithms.

## What is a Data Structure?

Data structures are simply ways to store data. The easiest example of a data structure is an array. Arrays are a collection of elements. Each element is identified by its index in the array.

```ruby
# Interactive Ruby Terminal

# creating an array of numbers
2.6.3 :001 > arr = [1, 2, 3, 4]
 => [1, 2, 3, 4]

# getting the element at index 0
2.6.3 :002 > arr[0]
 => 1
``` 

## Linked Lists

A Linked list is a linear collection of elements, essentially a chain of values connected to eachother. Each element is called a "node". Each node contains a value and a pointer to the next node. This is usually the first "advanced" data struture that is taught in class.

Here's a node class definition:

```ruby
class Node

	attr_accessor :val, :next

	def initialize(val)
		@val = val
		@next = nil
	end
end
```
Each node has two attributes: `val`, which holds a value, and `next`, which will be used to point to the next node and create a link between nodes.

Here's a simple 3-node linked list created using the class definition above:

```ruby
# create new nodes with default values
node_one = Node.new(1)
node_two = Node.new(2)
node_three = Node.new(3)

# link node one to node two
node_one.next = node_two
# link node two to node three
node_two.next = node_three

puts node_one.val # prints out '1'
puts node_one.next.val # prints out '2'
puts node_one.next.next.val # prints out '3'

```


